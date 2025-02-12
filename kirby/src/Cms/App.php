<?php

namespace Kirby\Cms;

use Kirby\Data\Data;
use Kirby\Email\PHPMailer as Emailer;
use Kirby\Exception\ErrorPageException;
use Kirby\Exception\InvalidArgumentException;
use Kirby\Exception\LogicException;
use Kirby\Exception\NotFoundException;
use Kirby\Filesystem\Dir;
use Kirby\Filesystem\F;
use Kirby\Http\Request;
use Kirby\Http\Router;
use Kirby\Http\Server;
use Kirby\Http\Uri;
use Kirby\Http\Visitor;
use Kirby\Session\AutoSession;
use Kirby\Text\KirbyTag;
use Kirby\Text\KirbyTags;
use Kirby\Toolkit\A;
use Kirby\Toolkit\Config;
use Kirby\Toolkit\Controller;
use Kirby\Toolkit\Properties;
use Throwable;

/**
 * The `$kirby` object is the app instance of
 * your Kirby installation. It's the central
 * starting point to get all the different
 * aspects of your site, like the options, urls,
 * roots, languages, roles, etc.
 *
 * @package   Kirby Cms
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier GmbH
 * @license   https://getkirby.com/license
 */
class App
{
    const CLASS_ALIAS = 'kirby';

    use AppCaches;
    use AppErrors;
    use AppPlugins;
    use AppTranslations;
    use AppUsers;
    use Properties;

    protected static $instance;
    protected static $version;

    public $data = [];

    protected $api;
    protected $collections;
    protected $defaultLanguage;
    protected $language;
    protected $languages;
    protected $locks;
    protected $multilang;
    protected $nonce;
    protected $options;
    protected $path;
    protected $request;
    protected $response;
    protected $roles;
    protected $roots;
    protected $routes;
    protected $router;
    protected $server;
    protected $sessionHandler;
    protected $site;
    protected $system;
    protected $urls;
    protected $user;
    protected $users;
    protected $visitor;

    /**
     * Creates a new App instance
     *
     * @param array $props
     * @param bool $setInstance If false, the instance won't be set globally
     */
    public function __construct(array $props = [], bool $setInstance = true)
    {
        // register all roots to be able to load stuff afterwards
        $this->bakeRoots($props['roots'] ?? []);

        // stuff from config and additional options
        $this->optionsFromConfig();
        $this->optionsFromProps($props['options'] ?? []);

        // register the Whoops error handler
        $this->handleErrors();

        // set the path to make it available for the url bakery
        $this->setPath($props['path'] ?? null);

        // create all urls after the config, so possible
        // options can be taken into account
        $this->bakeUrls($props['urls'] ?? []);

        // configurable properties
        $this->setOptionalProperties($props, [
            'languages',
            'request',
            'roles',
            'site',
            'user',
            'users'
        ]);

        // set the singleton
        if (static::$instance === null || $setInstance === true) {
            Model::$kirby = static::$instance = $this;
        }

        // setup the I18n class with the translation loader
        $this->i18n();

        // load all extensions
        $this->extensionsFromSystem();
        $this->extensionsFromProps($props);
        $this->extensionsFromPlugins();
        $this->extensionsFromOptions();
        $this->extensionsFromFolders();

        // trigger hook for use in plugins
        $this->trigger('system.loadPlugins:after');

        // execute a ready callback from the config
        $this->optionsFromReadyCallback();

        // bake config
        $this->bakeOptions();
    }

    /**
     * Improved `var_dump` output
     *
     * @return array
     */
    public function __debugInfo(): array
    {
        return [
            'languages' => $this->languages(),
            'options'   => $this->options(),
            'request'   => $this->request(),
            'roots'     => $this->roots(),
            'site'      => $this->site(),
            'urls'      => $this->urls(),
            'version'   => $this->version(),
        ];
    }

    /**
     * Returns the Api instance
     *
     * @internal
     * @return \Kirby\Cms\Api
     */
    public function api()
    {
        if ($this->api !== null) {
            return $this->api;
        }

        $root       = $this->root('kirby') . '/config/api';
        $extensions = $this->extensions['api'] ?? [];
        $routes     = (include $root . '/routes.php')($this);

        $api = [
            'debug'          => $this->option('debug', false),
            'authentication' => $extensions['authentication'] ?? include $root . '/authentication.php',
            'data'           => $extensions['data']           ?? [],
            'collections'    => array_merge($extensions['collections'] ?? [], include $root . '/collections.php'),
            'models'         => array_merge($extensions['models']      ?? [], include $root . '/models.php'),
            'routes'         => array_merge($routes, $extensions['routes'] ?? []),
            'kirby'          => $this,
        ];

        return $this->api = new Api($api);
    }

    /**
     * Applies a hook to the given value
     *
     * @internal
     * @param string $name Full event name
     * @param array $args Associative array of named event arguments
     * @param string $modify Key in $args that is modified by the hooks
     * @param \Kirby\Cms\Event|null $originalEvent Event object (internal use)
     * @return mixed Resulting value as modified by the hooks
     */
    public function apply(string $name, array $args, string $modify, ?Event $originalEvent = null)
    {
        $event = $originalEvent ?? new Event($name, $args);

        if ($functions = $this->extension('hooks', $name)) {
            foreach ($functions as $function) {
                // bind the App object to the hook
                $newValue = $event->call($this, $function);

                // update value if one was returned
                if ($newValue !== null) {
                    $event->updateArgument($modify, $newValue);
                }
            }
        }

        // apply wildcard hooks if available
        $nameWildcards = $event->nameWildcards();
        if ($originalEvent === null && count($nameWildcards) > 0) {
            foreach ($nameWildcards as $nameWildcard) {
                // the $event object is passed by reference
                // and will be modified down the chain
                $this->apply($nameWildcard, $event->arguments(), $modify, $event);
            }
        }

        return $event->argument($modify);
    }

    /**
     * Normalizes and globally sets the configured options
     *
     * @return $this
     */
    protected function bakeOptions()
    {
        // convert the old plugin option syntax to the new one
        foreach ($this->options as $key => $value) {
            // detect option keys with the `vendor.plugin.option` format
            if (preg_match('/^([a-z0-9-]+\.[a-z0-9-]+)\.(.*)$/i', $key, $matches) === 1) {
                list(, $plugin, $option) = $matches;

                // verify that it's really a plugin option
                if (isset(static::$plugins[str_replace('.', '/', $plugin)]) !== true) {
                    continue;
                }

                // ensure that the target option array exists
                // (which it will if the plugin has any options)
                if (isset($this->options[$plugin]) !== true) {
                    $this->options[$plugin] = []; // @codeCoverageIgnore
                }

                // move the option to the plugin option array
                // don't overwrite nested arrays completely but merge them
                $this->options[$plugin] = array_replace_recursive($this->options[$plugin], [$option => $value]);
                unset($this->options[$key]);
            }
        }

        Config::$data = $this->options;
        return $this;
    }

    /**
     * Sets the directory structure
     *
     * @param array|null $roots
     * @return $this
     */
    protected function bakeRoots(array $roots = null)
    {
        $roots = array_merge(require dirname(__DIR__, 2) . '/config/roots.php', (array)$roots);
        $this->roots = Ingredients::bake($roots);
        return $this;
    }

    /**
     * Sets the Url structure
     *
     * @param array|null $urls
     * @return $this
     */
    protected function bakeUrls(array $urls = null)
    {
        // inject the index URL from the config
        if (isset($this->options['url']) === true) {
            $urls['index'] = $this->options['url'];
        }

        $urls = array_merge(require $this->root('kirby') . '/config/urls.php', (array)$urls);
        $this->urls = Ingredients::bake($urls);
        return $this;
    }

    /**
     * Returns all available blueprints for this installation
     *
     * @param string $type
     * @return array
     */
    public function blueprints(string $type = 'pages'): array
    {
        $blueprints = [];

        foreach ($this->extensions('blueprints') as $name => $blueprint) {
            if (dirname($name) === $type) {
                $name = basename($name);
                $blueprints[$name] = $name;
            }
        }

        foreach (glob($this->root('blueprints') . '/' . $type . '/*.yml') as $blueprint) {
            $name = F::name($blueprint);
            $blueprints[$name] = $name;
        }

        ksort($blueprints);

        return array_values($blueprints);
    }

    /**
     * Calls any Kirby route
     *
     * @param string|null $path
     * @param string|null $method
     * @return mixed
     */
    public function call(string $path = null, string $method = null)
    {
        $router = $this->router();

        $router::$beforeEach = function ($route, $path, $method) {
            $this->trigger('route:before', compact('route', 'path', 'method'));
        };

        $router::$afterEach = function ($route, $path, $method, $result, $final) {
            return $this->apply('route:after', compact('route', 'path', 'method', 'result', 'final'), 'result');
        };

        return $router->call($path ?? $this->path(), $method ?? $this->request()->method());
    }

    /**
     * Creates an instance with the same
     * initial properties
     *
     * @param array $props
     * @param bool $setInstance If false, the instance won't be set globally
     * @return static
     */
    public function clone(array $props = [], bool $setInstance = true)
    {
        $props = array_replace_recursive($this->propertyData, $props);

        $clone = new static($props, $setInstance);
        $clone->data = $this->data;

        return $clone;
    }

    /**
     * Returns a specific user-defined collection
     * by name. All relevant dependencies are
     * automatically injected
     *
     * @param string $name
     * @return \Kirby\Cms\Collection|null
     */
    public function collection(string $name)
    {
        return $this->collections()->get($name, [
            'kirby' => $this,
            'site'  => $this->site(),
            'pages' => $this->site()->children(),
            'users' => $this->users()
        ]);
    }

    /**
     * Returns all user-defined collections
     *
     * @return \Kirby\Cms\Collections
     */
    public function collections()
    {
        return $this->collections = $this->collections ?? new Collections();
    }

    /**
     * Returns a core component
     *
     * @internal
     * @param string $name
     * @return mixed
     */
    public function component($name)
    {
        return $this->extensions['components'][$name] ?? null;
    }

    /**
     * Returns the content extension
     *
     * @internal
     * @return string
     */
    public function contentExtension(): string
    {
        return $this->options['content']['extension'] ?? 'txt';
    }

    /**
     * Returns files that should be ignored when scanning folders
     *
     * @internal
     * @return array
     */
    public function contentIgnore(): array
    {
        return $this->options['content']['ignore'] ?? Dir::$ignore;
    }

    /**
     * Generates a non-guessable token based on model
     * data and a configured salt
     *
     * @param mixed $model Object to pass to the salt callback if configured
     * @param string $value Model data to include in the generated token
     * @return string
     */
    public function contentToken($model, string $value): string
    {
        if (method_exists($model, 'root') === true) {
            $default = $model->root();
        } else {
            $default = $this->root('content');
        }

        $salt = $this->option('content.salt', $default);

        if (is_a($salt, 'Closure') === true) {
            $salt = $salt($model);
        }

        return hash_hmac('sha1', $value, $salt);
    }

    /**
     * Calls a page controller by name
     * and with the given arguments
     *
     * @internal
     * @param string $name
     * @param array $arguments
     * @param string $contentType
     * @return array
     */
    public function controller(string $name, array $arguments = [], string $contentType = 'html'): array
    {
        $name = basename(strtolower($name));

        if ($controller = $this->controllerLookup($name, $contentType)) {
            return (array)$controller->call($this, $arguments);
        }

        if ($contentType !== 'html') {

            // no luck for a specific representation controller?
            // let's try the html controller instead
            if ($controller = $this->controllerLookup($name)) {
                return (array)$controller->call($this, $arguments);
            }
        }

        // still no luck? Let's take the site controller
        if ($controller = $this->controllerLookup('site')) {
            return (array)$controller->call($this, $arguments);
        }

        return [];
    }

    /**
     * Try to find a controller by name
     *
     * @param string $name
     * @param string $contentType
     * @return \Kirby\Toolkit\Controller|null
     */
    protected function controllerLookup(string $name, string $contentType = 'html')
    {
        if ($contentType !== null && $contentType !== 'html') {
            $name .= '.' . $contentType;
        }

        // controller on disk
        if ($controller = Controller::load($this->root('controllers') . '/' . $name . '.php')) {
            return $controller;
        }

        // registry controller
        if ($controller = $this->extension('controllers', $name)) {
            return is_a($controller, 'Kirby\Toolkit\Controller') ? $controller : new Controller($controller);
        }

        return null;
    }

    /**
     * Returns the default language object
     *
     * @return \Kirby\Cms\Language|null
     */
    public function defaultLanguage()
    {
        return $this->defaultLanguage = $this->defaultLanguage ?? $this->languages()->default();
    }

    /**
     * Destroy the instance singleton and
     * purge other static props
     *
     * @internal
     */
    public static function destroy(): void
    {
        static::$plugins  = [];
        static::$instance = null;
    }

    /**
     * Detect the preferred language from the visitor object
     *
     * @return \Kirby\Cms\Language
     */
    public function detectedLanguage()
    {
        $languages = $this->languages();
        $visitor   = $this->visitor();

        foreach ($visitor->acceptedLanguages() as $lang) {
            if ($language = $languages->findBy('locale', $lang->locale(LC_ALL))) {
                return $language;
            }
        }

        foreach ($visitor->acceptedLanguages() as $lang) {
            if ($language = $languages->findBy('code', $lang->code())) {
                return $language;
            }
        }

        return $this->defaultLanguage();
    }

    /**
     * Returns the Email singleton
     *
     * @param mixed $preset
     * @param array $props
     * @return \Kirby\Email\PHPMailer
     */
    public function email($preset = [], array $props = [])
    {
        return new Emailer((new Email($preset, $props))->toArray(), $props['debug'] ?? false);
    }

    /**
     * Finds any file in the content directory
     *
     * @param string $path
     * @param mixed $parent
     * @param bool $drafts
     * @return \Kirby\Cms\File|null
     */
    public function file(string $path, $parent = null, bool $drafts = true)
    {
        $parent   = $parent ?? $this->site();
        $id       = dirname($path);
        $filename = basename($path);

        if (is_a($parent, 'Kirby\Cms\User') === true) {
            return $parent->file($filename);
        }

        if (is_a($parent, 'Kirby\Cms\File') === true) {
            $parent = $parent->parent();
        }

        if ($id === '.') {
            if ($file = $parent->file($filename)) {
                return $file;
            } elseif ($file = $this->site()->file($filename)) {
                return $file;
            } else {
                return null;
            }
        }

        if ($page = $this->page($id, $parent, $drafts)) {
            return $page->file($filename);
        }

        if ($page = $this->page($id, null, $drafts)) {
            return $page->file($filename);
        }

        return null;
    }

    /**
     * Returns the current App instance
     *
     * @param \Kirby\Cms\App|null $instance
     * @param bool $lazy If `true`, the instance is only returned if already existing
     * @return static|null
     */
    public static function instance(self $instance = null, bool $lazy = false)
    {
        if ($instance === null) {
            if ($lazy === true) {
                return static::$instance;
            } else {
                return static::$instance ?? new static();
            }
        }

        return static::$instance = $instance;
    }

    /**
     * Takes almost any kind of input and
     * tries to convert it into a valid response
     *
     * @internal
     * @param mixed $input
     * @return \Kirby\Http\Response
     */
    public function io($input)
    {
        // use the current response configuration
        $response = $this->response();

        // any direct exception will be turned into an error page
        if (is_a($input, 'Throwable') === true) {
            if (is_a($input, 'Kirby\Exception\Exception') === true) {
                $code = $input->getHttpCode();
            } else {
                $code = $input->getCode();
            }
            $message = $input->getMessage();

            if ($code < 400 || $code > 599) {
                $code = 500;
            }

            if ($errorPage = $this->site()->errorPage()) {
                return $response->code($code)->send($errorPage->render([
                    'errorCode'    => $code,
                    'errorMessage' => $message,
                    'errorType'    => get_class($input)
                ]));
            }

            return $response
                ->code($code)
                ->type('text/html')
                ->send($message);
        }

        // Empty input
        if (empty($input) === true) {
            return $this->io(new NotFoundException());
        }

        // Response Configuration
        if (is_a($input, 'Kirby\Cms\Responder') === true) {
            return $input->send();
        }

        // Responses
        if (is_a($input, 'Kirby\Http\Response') === true) {
            return $input;
        }

        // Pages
        if (is_a($input, 'Kirby\Cms\Page')) {
            try {
                $html = $input->render();
            } catch (ErrorPageException $e) {
                return $this->io($e);
            }

            if ($input->isErrorPage() === true) {
                if ($response->code() === null) {
                    $response->code(404);
                }
            }

            return $response->send($html);
        }

        // Files
        if (is_a($input, 'Kirby\Cms\File')) {
            return $response->redirect($input->mediaUrl(), 307)->send();
        }

        // Simple HTML response
        if (is_string($input) === true) {
            return $response->send($input);
        }

        // array to json conversion
        if (is_array($input) === true) {
            return $response->json($input)->send();
        }

        throw new InvalidArgumentException('Unexpected input');
    }

    /**
     * Renders a single KirbyTag with the given attributes
     *
     * @internal
     * @param string $type
     * @param string|null $value
     * @param array $attr
     * @param array $data
     * @return string
     */
    public function kirbytag(string $type, string $value = null, array $attr = [], array $data = []): string
    {
        $data['kirby']  = $data['kirby']  ?? $this;
        $data['site']   = $data['site']   ?? $data['kirby']->site();
        $data['parent'] = $data['parent'] ?? $data['site']->page();

        return (new KirbyTag($type, $value, $attr, $data, $this->options))->render();
    }

    /**
     * KirbyTags Parser
     *
     * @internal
     * @param string|null $text
     * @param array $data
     * @return string
     */
    public function kirbytags(string $text = null, array $data = []): string
    {
        $data['kirby']  = $data['kirby']  ?? $this;
        $data['site']   = $data['site']   ?? $data['kirby']->site();
        $data['parent'] = $data['parent'] ?? $data['site']->page();
        $options        = $this->options;

        $text = $this->apply('kirbytags:before', compact('text', 'data', 'options'), 'text');
        $text = KirbyTags::parse($text, $data, $options);
        $text = $this->apply('kirbytags:after', compact('text', 'data', 'options'), 'text');

        return $text;
    }

    /**
     * Parses KirbyTags first and Markdown afterwards
     *
     * @internal
     * @param string|null $text
     * @param array $data
     * @param bool $inline
     * @return string
     */
    public function kirbytext(string $text = null, array $data = [], bool $inline = false): string
    {
        $text = $this->apply('kirbytext:before', compact('text'), 'text');
        $text = $this->kirbytags($text, $data);
        $text = $this->markdown($text, $inline);

        if ($this->option('smartypants', false) !== false) {
            $text = $this->smartypants($text);
        }

        $text = $this->apply('kirbytext:after', compact('text'), 'text');

        return $text;
    }

    /**
     * Returns the current language
     *
     * @param string|null $code
     * @return \Kirby\Cms\Language|null
     */
    public function language(string $code = null)
    {
        if ($this->multilang() === false) {
            return null;
        }

        if ($code === 'default') {
            return $this->languages()->default();
        }

        if ($code !== null) {
            return $this->languages()->find($code);
        }

        return $this->language = $this->language ?? $this->languages()->default();
    }

    /**
     * Returns the current language code
     *
     * @internal
     * @param string|null $languageCode
     * @return string|null
     */
    public function languageCode(string $languageCode = null): ?string
    {
        if ($language = $this->language($languageCode)) {
            return $language->code();
        }

        return null;
    }

    /**
     * Returns all available site languages
     *
     * @param bool
     * @return \Kirby\Cms\Languages
     */
    public function languages(bool $clone = true)
    {
        if ($this->languages !== null) {
            return $clone === true ? clone $this->languages : $this->languages;
        }

        return $this->languages = Languages::load();
    }

    /**
     * Returns the app's locks object
     *
     * @return \Kirby\Cms\ContentLocks
     */
    public function locks(): ContentLocks
    {
        if ($this->locks !== null) {
            return $this->locks;
        }

        return $this->locks = new ContentLocks();
    }

    /**
     * Parses Markdown
     *
     * @internal
     * @param string|null $text
     * @param bool $inline
     * @return string
     */
    public function markdown(string $text = null, bool $inline = false): string
    {
        return ($this->component('markdown'))($this, $text, $this->options['markdown'] ?? [], $inline);
    }

    /**
     * Check for a multilang setup
     *
     * @return bool
     */
    public function multilang(): bool
    {
        if ($this->multilang !== null) {
            return $this->multilang;
        }

        return $this->multilang = $this->languages()->count() !== 0;
    }

    /**
     * Returns the nonce, which is used
     * in the panel for inline scripts
     * @since 3.3.0
     *
     * @return string
     */
    public function nonce(): string
    {
        return $this->nonce = $this->nonce ?? base64_encode(random_bytes(20));
    }

    /**
     * Load a specific configuration option
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function option(string $key, $default = null)
    {
        return A::get($this->options, $key, $default);
    }

    /**
     * Returns all configuration options
     *
     * @return array
     */
    public function options(): array
    {
        return $this->options;
    }

    /**
     * Load all options from files in site/config
     *
     * @return array
     */
    protected function optionsFromConfig(): array
    {
        $server = $this->server();
        $root   = $this->root('config');

        Config::$data = [];

        $main   = F::load($root . '/config.php', []);
        $host   = F::load($root . '/config.' . basename($server->host()) . '.php', []);
        $addr   = F::load($root . '/config.' . basename($server->address()) . '.php', []);

        $config = Config::$data;

        return $this->options = array_replace_recursive($config, $main, $host, $addr);
    }

    /**
     * Inject options from Kirby instance props
     *
     * @param array $options
     * @return array
     */
    protected function optionsFromProps(array $options = []): array
    {
        return $this->options = array_replace_recursive($this->options, $options);
    }

    /**
     * Merge last-minute options from ready callback
     *
     * @return array
     */
    protected function optionsFromReadyCallback(): array
    {
        if (isset($this->options['ready']) === true && is_callable($this->options['ready']) === true) {
            // fetch last-minute options from the callback
            $options = (array)$this->options['ready']($this);

            // inject all last-minute options recursively
            $this->options = array_replace_recursive($this->options, $options);

            // update the system with changed options
            if (
                isset($options['debug']) === true ||
                isset($options['whoops']) === true ||
                isset($options['editor']) === true
            ) {
                $this->handleErrors();
            }

            if (isset($options['debug']) === true) {
                $this->api = null;
            }

            if (isset($options['home']) === true || isset($options['error']) === true) {
                $this->site = null;
            }

            // checks custom language definition for slugs
            if ($slugsOption = $this->option('slugs')) {
                // slugs option must be set to string or "slugs" => ["language" => "de"] as array
                if (is_string($slugsOption) === true || isset($slugsOption['language']) === true) {
                    $this->i18n();
                }
            }
        }

        return $this->options;
    }

    /**
     * Returns any page from the content folder
     *
     * @param string|null $id
     * @param \Kirby\Cms\Page|\Kirby\Cms\Site|null $parent
     * @param bool $drafts
     * @return \Kirby\Cms\Page|null
     */
    public function page(?string $id = null, $parent = null, bool $drafts = true)
    {
        if ($id === null) {
            return null;
        }

        $parent = $parent ?? $this->site();

        if ($page = $parent->find($id)) {
            /**
             * We passed a single $id, we can be sure that the result is
             * @var \Kirby\Cms\Page $page
             */
            return $page;
        }

        if ($drafts === true && $draft = $parent->draft($id)) {
            return $draft;
        }

        return null;
    }

    /**
     * Returns the request path
     *
     * @return string
     */
    public function path(): string
    {
        if (is_string($this->path) === true) {
            return $this->path;
        }

        $requestUri  = '/' . $this->request()->url()->path();
        $scriptName  = $_SERVER['SCRIPT_NAME'];
        $scriptFile  = basename($scriptName);
        $scriptDir   = dirname($scriptName);
        $scriptPath  = $scriptFile === 'index.php' ? $scriptDir : $scriptName;
        $requestPath = preg_replace('!^' . preg_quote($scriptPath) . '!', '', $requestUri);

        return $this->setPath($requestPath)->path;
    }

    /**
     * Returns the Response object for the
     * current request
     *
     * @param string|null $path
     * @param string|null $method
     * @return \Kirby\Http\Response
     */
    public function render(string $path = null, string $method = null)
    {
        return $this->io($this->call($path, $method));
    }

    /**
     * Returns the Request singleton
     *
     * @return \Kirby\Http\Request
     */
    public function request()
    {
        return $this->request = $this->request ?? new Request();
    }

    /**
     * Path resolver for the router
     *
     * @internal
     * @param string|null $path
     * @param string|null $language
     * @return mixed
     * @throws \Kirby\Exception\NotFoundException if the home page cannot be found
     */
    public function resolve(string $path = null, string $language = null)
    {
        // set the current translation
        $this->setCurrentTranslation($language);

        // set the current locale
        $this->setCurrentLanguage($language);

        // the site is needed a couple times here
        $site = $this->site();

        // use the home page
        if ($path === null) {
            if ($homePage = $site->homePage()) {
                return $homePage;
            }

            throw new NotFoundException('The home page does not exist');
        }

        // search for the page by path
        $page = $site->find($path);

        // search for a draft if the page cannot be found
        if (!$page && $draft = $site->draft($path)) {
            if ($this->user() || $draft->isVerified(get('token'))) {
                $page = $draft;
            }
        }

        // try to resolve content representations if the path has an extension
        $extension = F::extension($path);

        // no content representation? then return the page
        if (empty($extension) === true) {
            return $page;
        }

        // only try to return a representation
        // when the page has been found
        if ($page) {
            try {
                $response = $this->response();
                $output   = $page->render([], $extension);

                // attach a MIME type based on the representation
                // only if no custom MIME type was set
                if ($response->type() === null) {
                    $response->type($extension);
                }

                return $response->body($output);
            } catch (NotFoundException $e) {
                return null;
            }
        }

        $id       = dirname($path);
        $filename = basename($path);

        // try to resolve image urls for pages and drafts
        if ($page = $site->findPageOrDraft($id)) {
            return $page->file($filename);
        }

        // try to resolve site files at least
        return $site->file($filename);
    }

    /**
     * Response configuration
     *
     * @return \Kirby\Cms\Responder
     */
    public function response()
    {
        return $this->response = $this->response ?? new Responder();
    }

    /**
     * Returns all user roles
     *
     * @return \Kirby\Cms\Roles
     */
    public function roles()
    {
        return $this->roles = $this->roles ?? Roles::load($this->root('roles'));
    }

    /**
     * Returns a system root
     *
     * @param string $type
     * @return string
     */
    public function root(string $type = 'index'): string
    {
        return $this->roots->__get($type);
    }

    /**
     * Returns the directory structure
     *
     * @return \Kirby\Cms\Ingredients
     */
    public function roots()
    {
        return $this->roots;
    }

    /**
     * Returns the currently active route
     *
     * @return \Kirby\Http\Route|null
     */
    public function route()
    {
        return $this->router()->route();
    }

    /**
     * Returns the Router singleton
     *
     * @internal
     * @return \Kirby\Http\Router
     */
    public function router()
    {
        $routes = $this->routes();

        if ($this->multilang() === true) {
            foreach ($routes as $index => $route) {
                if (empty($route['language']) === false) {
                    unset($routes[$index]);
                }
            }
        }

        return $this->router = $this->router ?? new Router($routes);
    }

    /**
     * Returns all defined routes
     *
     * @internal
     * @return array
     */
    public function routes(): array
    {
        if (is_array($this->routes) === true) {
            return $this->routes;
        }

        $registry = $this->extensions('routes');
        $system   = (include $this->root('kirby') . '/config/routes.php')($this);
        $routes   = array_merge($system['before'], $registry, $system['after']);

        return $this->routes = $routes;
    }

    /**
     * Returns the current session object
     *
     * @param array $options Additional options, see the session component
     * @return \Kirby\Session\Session
     */
    public function session(array $options = [])
    {
        // never cache responses that depend on the session
        $this->response()->cache(false);
        $this->response()->header('Cache-Control', 'no-store', true);

        return $this->sessionHandler()->get($options);
    }

    /**
     * Returns the session handler
     *
     * @return \Kirby\Session\AutoSession
     */
    public function sessionHandler()
    {
        $this->sessionHandler = $this->sessionHandler ?? new AutoSession($this->root('sessions'), $this->option('session', []));
        return $this->sessionHandler;
    }

    /**
     * Create your own set of languages
     *
     * @param array|null $languages
     * @return $this
     */
    protected function setLanguages(array $languages = null)
    {
        if ($languages !== null) {
            $objects = [];

            foreach ($languages as $props) {
                $objects[] = new Language($props);
            }

            $this->languages = new Languages($objects);
        }

        return $this;
    }

    /**
     * Sets the request path that is
     * used for the router
     *
     * @param string|null $path
     * @return $this
     */
    protected function setPath(string $path = null)
    {
        $this->path = $path !== null ? trim($path, '/') : null;
        return $this;
    }

    /**
     * Sets the request
     *
     * @param array|null $request
     * @return $this
     */
    protected function setRequest(array $request = null)
    {
        if ($request !== null) {
            $this->request = new Request($request);
        }

        return $this;
    }

    /**
     * Create your own set of roles
     *
     * @param array|null $roles
     * @return $this
     */
    protected function setRoles(array $roles = null)
    {
        if ($roles !== null) {
            $this->roles = Roles::factory($roles, [
                'kirby' => $this
            ]);
        }

        return $this;
    }

    /**
     * Sets a custom Site object
     *
     * @param \Kirby\Cms\Site|array|null $site
     * @return $this
     */
    protected function setSite($site = null)
    {
        if (is_array($site) === true) {
            $site = new Site($site + [
                'kirby' => $this
            ]);
        }

        $this->site = $site;
        return $this;
    }

    /**
     * Returns the Server object
     *
     * @return \Kirby\Http\Server
     */
    public function server()
    {
        return $this->server = $this->server ?? new Server();
    }

    /**
     * Initializes and returns the Site object
     *
     * @return \Kirby\Cms\Site
     */
    public function site()
    {
        return $this->site = $this->site ?? new Site([
            'errorPageId' => $this->options['error'] ?? 'error',
            'homePageId'  => $this->options['home']  ?? 'home',
            'kirby'       => $this,
            'url'         => $this->url('index'),
        ]);
    }

    /**
     * Applies the smartypants rule on the text
     *
     * @internal
     * @param string|null $text
     * @return string
     */
    public function smartypants(string $text = null): string
    {
        $options = $this->option('smartypants', []);

        if ($options === false) {
            return $text;
        } elseif (is_array($options) === false) {
            $options = [];
        }

        if ($this->multilang() === true) {
            $languageSmartypants = $this->language()->smartypants() ?? [];

            if (empty($languageSmartypants) === false) {
                $options = array_merge($options, $languageSmartypants);
            }
        }

        return ($this->component('smartypants'))($this, $text, $options);
    }

    /**
     * Uses the snippet component to create
     * and return a template snippet
     *
     * @internal
     * @param mixed $name
     * @param array $data
     * @return string|null
     */
    public function snippet($name, array $data = []): ?string
    {
        return ($this->component('snippet'))($this, $name, array_merge($this->data, $data));
    }

    /**
     * System check class
     *
     * @return \Kirby\Cms\System
     */
    public function system()
    {
        return $this->system = $this->system ?? new System($this);
    }

    /**
     * Uses the template component to initialize
     * and return the Template object
     *
     * @internal
     * @return \Kirby\Cms\Template
     * @param string $name
     * @param string $type
     * @param string $defaultType
     */
    public function template(string $name, string $type = 'html', string $defaultType = 'html')
    {
        return ($this->component('template'))($this, $name, $type, $defaultType);
    }

    /**
     * Thumbnail creator
     *
     * @param string $src
     * @param string $dst
     * @param array $options
     * @return string
     */
    public function thumb(string $src, string $dst, array $options = []): string
    {
        return ($this->component('thumb'))($this, $src, $dst, $options);
    }

    /**
     * Trigger a hook by name
     *
     * @internal
     * @param string $name Full event name
     * @param array $args Associative array of named event arguments
     * @param \Kirby\Cms\Event|null $originalEvent Event object (internal use)
     * @return void
     */
    public function trigger(string $name, array $args = [], ?Event $originalEvent = null)
    {
        $event = $originalEvent ?? new Event($name, $args);

        if ($functions = $this->extension('hooks', $name)) {
            static $level = 0;
            static $triggered = [];
            $level++;

            foreach ($functions as $index => $function) {
                if (in_array($function, $triggered[$name] ?? []) === true) {
                    continue;
                }

                // mark the hook as triggered, to avoid endless loops
                $triggered[$name][] = $function;

                // bind the App object to the hook
                $event->call($this, $function);
            }

            $level--;

            if ($level === 0) {
                $triggered = [];
            }
        }

        // trigger wildcard hooks if available
        $nameWildcards = $event->nameWildcards();
        if ($originalEvent === null && count($nameWildcards) > 0) {
            foreach ($nameWildcards as $nameWildcard) {
                $this->trigger($nameWildcard, $args, $event);
            }
        }
    }

    /**
     * Returns a system url
     *
     * @param string $type
     * @param bool $object If set to `true`, the URL is converted to an object
     * @return string|\Kirby\Http\Uri
     */
    public function url(string $type = 'index', bool $object = false)
    {
        $url = $this->urls->__get($type);

        if ($object === true) {
            if (Url::isAbsolute($url)) {
                return Url::toObject($url);
            }

            // index URL was configured without host, use the current host
            return Uri::current([
                'path'   => $url,
                'query'  => null
            ]);
        }

        return $url;
    }

    /**
     * Returns the url structure
     *
     * @return \Kirby\Cms\Ingredients
     */
    public function urls()
    {
        return $this->urls;
    }

    /**
     * Returns the current version number from
     * the composer.json (Keep that up to date! :))
     *
     * @return string|null
     * @throws \Kirby\Exception\LogicException if the Kirby version cannot be detected
     */
    public static function version(): ?string
    {
        try {
            return static::$version = static::$version ?? Data::read(dirname(__DIR__, 2) . '/composer.json')['version'] ?? null;
        } catch (Throwable $e) {
            throw new LogicException('The Kirby version cannot be detected. The composer.json is probably missing or not readable.');
        }
    }

    /**
     * Creates a hash of the version number
     *
     * @return string
     */
    public static function versionHash(): string
    {
        return md5(static::version());
    }

    /**
     * Returns the visitor object
     *
     * @return \Kirby\Http\Visitor
     */
    public function visitor()
    {
        return $this->visitor = $this->visitor ?? new Visitor();
    }
}
