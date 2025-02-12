<?php

use Kirby\Cms\App;
use Kirby\Cms\Html;
use Kirby\Cms\Response;
use Kirby\Cms\Url;
use Kirby\Exception\InvalidArgumentException;
use Kirby\Filesystem\Asset;
use Kirby\Filesystem\F;
use Kirby\Http\Router;
use Kirby\Toolkit\Escape;
use Kirby\Toolkit\I18n;
use Kirby\Toolkit\Str;
use Kirby\Toolkit\V;

/**
 * Helper to create an asset object
 *
 * @param string $path
 * @return \Kirby\Filesystem\Asset
 */
function asset(string $path)
{
    return new Asset($path);
}

/**
 * Generates a list of HTML attributes
 *
 * @param array|null $attr A list of attributes as key/value array
 * @param string|null $before An optional string that will be prepended if the result is not empty
 * @param string|null $after An optional string that will be appended if the result is not empty
 * @return string|null
 */
function attr(?array $attr = null, ?string $before = null, ?string $after = null): ?string
{
    if ($attrs = Html::attr($attr)) {
        return $before . $attrs . $after;
    }

    return null;
}

/**
 * Returns the result of a collection by name
 *
 * @param string $name
 * @return \Kirby\Cms\Collection|null
 */
function collection(string $name)
{
    return App::instance()->collection($name);
}

/**
 * Checks / returns a CSRF token
 *
 * @param string|null $check Pass a token here to compare it to the one in the session
 * @return string|bool Either the token or a boolean check result
 */
function csrf(?string $check = null)
{
    $session = App::instance()->session();

    // no arguments, generate/return a token
    // (check explicitly if there have been no arguments at all;
    // checking for null introduces a security issue because null could come
    // from user input or bugs in the calling code!)
    if (func_num_args() === 0) {
        $token = $session->get('kirby.csrf');

        if (is_string($token) !== true) {
            $token = bin2hex(random_bytes(32));
            $session->set('kirby.csrf', $token);
        }

        return $token;
    }

    // argument has been passed, check the token
    if (
        is_string($check) === true &&
        is_string($session->get('kirby.csrf')) === true
    ) {
        return hash_equals($session->get('kirby.csrf'), $check) === true;
    }

    return false;
}

/**
 * Creates one or multiple CSS link tags
 *
 * @param string|array $url Relative or absolute URLs, an array of URLs or `@auto` for automatic template css loading
 * @param string|array $options Pass an array of attributes for the link tag or a media attribute string
 * @return string|null
 */
function css($url, $options = null): ?string
{
    if (is_array($url) === true) {
        $links = array_map(function ($url) use ($options) {
            return css($url, $options);
        }, $url);

        return implode(PHP_EOL, $links);
    }

    if (is_string($options) === true) {
        $options = ['media' => $options];
    }

    $kirby = App::instance();

    if ($url === '@auto') {
        if (!$url = Url::toTemplateAsset('css/templates', 'css')) {
            return null;
        }
    }

    $url  = ($kirby->component('css'))($kirby, $url, $options);
    $url  = Url::to($url);
    $attr = array_merge((array)$options, [
        'href' => $url,
        'rel'  => 'stylesheet'
    ]);

    return '<link ' . attr($attr) . '>';
}

/**
 * Triggers a deprecation warning if debug mode is active
 * @since 3.3.0
 *
 * @param string $message
 * @return bool Whether the warning was triggered
 */
function deprecated(string $message): bool
{
    if (App::instance()->option('debug') === true) {
        return trigger_error($message, E_USER_DEPRECATED) === true;
    }

    return false;
}

if (function_exists('dump') === false) {
    /**
     * Simple object and variable dumper
     * to help with debugging.
     *
     * @param mixed $variable
     * @param bool $echo
     * @return string
     */
    function dump($variable, bool $echo = true): string
    {
        $kirby = App::instance();
        return ($kirby->component('dump'))($kirby, $variable, $echo);
    }
}

if (function_exists('e') === false) {
    /**
     * Smart version of echo with an if condition as first argument
     *
     * @param mixed $condition
     * @param mixed $value The string to be echoed if the condition is true
     * @param mixed $alternative An alternative string which should be echoed when the condition is false
     */
    function e($condition, $value, $alternative = null)
    {
        echo r($condition, $value, $alternative);
    }
}

/**
 * Escape context specific output
 *
 * @param string $string Untrusted data
 * @param string $context Location of output (`html`, `attr`, `js`, `css`, `url` or `xml`)
 * @return string Escaped data
 */
function esc(string $string, string $context = 'html'): string
{
    if (method_exists('Kirby\Toolkit\Escape', $context) === true) {
        return Escape::$context($string);
    }

    return $string;
}


/**
 * Shortcut for $kirby->request()->get()
 *
 * @param mixed $key The key to look for. Pass false or null to return the entire request array.
 * @param mixed $default Optional default value, which should be returned if no element has been found
 * @return mixed
 */
function get($key = null, $default = null)
{
    return App::instance()->request()->get($key, $default);
}

/**
 * Embeds a Github Gist
 *
 * @param string $url
 * @param string|null $file
 * @return string
 */
function gist(string $url, ?string $file = null): string
{
    return kirbytag([
        'gist' => $url,
        'file' => $file,
    ]);
}

/**
 * Redirects to the given Urls
 * Urls can be relative or absolute.
 *
 * @param string $url
 * @param int $code
 * @return void
 */
function go(string $url = '/', int $code = 302)
{
    die(Response::redirect($url, $code));
}

/**
 * Shortcut for html()
 *
 * @param string|null $string unencoded text
 * @param bool $keepTags
 * @return string
 */
function h(?string $string, bool $keepTags = false)
{
    return Html::encode($string, $keepTags);
}

/**
 * Creates safe html by encoding special characters
 *
 * @param string|null $string unencoded text
 * @param bool $keepTags
 * @return string
 */
function html(?string $string, bool $keepTags = false)
{
    return Html::encode($string, $keepTags);
}

/**
 * Return an image from any page
 * specified by the path
 *
 * Example:
 * <?= image('some/page/myimage.jpg') ?>
 *
 * @param string|null $path
 * @return \Kirby\Cms\File|null
 */
function image(?string $path = null)
{
    if ($path === null) {
        return page()->image();
    }

    $uri      = dirname($path);
    $filename = basename($path);

    if ($uri === '.') {
        $uri = null;
    }

    switch ($uri) {
        case '/':
            $parent = site();
            break;
        case null:
            $parent = page();
            break;
        default:
            $parent = page($uri);
            break;
    }

    if ($parent) {
        return $parent->image($filename);
    } else {
        return null;
    }
}

/**
 * Runs a number of validators on a set of data and checks if the data is invalid
 *
 * @param array $data
 * @param array $rules
 * @param array $messages
 * @return array
 */
function invalid(array $data = [], array $rules = [], array $messages = []): array
{
    $errors = [];

    foreach ($rules as $field => $validations) {
        $validationIndex = -1;

        // See: http://php.net/manual/en/types.comparisons.php
        // only false for: null, undefined variable, '', []
        $value   = $data[$field] ?? null;
        $filled  = $value !== null && $value !== '' && $value !== [];
        $message = $messages[$field] ?? $field;

        // True if there is an error message for each validation method.
        $messageArray = is_array($message);

        foreach ($validations as $method => $options) {
            // If the index is numeric, there is no option
            // and `$value` is sent directly as a `$options` parameter
            if (is_numeric($method) === true) {
                $method  = $options;
                $options = [$value];
            } else {
                if (is_array($options) === false) {
                    $options = [$options];
                }

                array_unshift($options, $value);
            }

            $validationIndex++;

            if ($method === 'required') {
                if ($filled) {
                    // Field is required and filled.
                    continue;
                }
            } elseif ($filled) {
                if (V::$method(...$options) === true) {
                    // Field is filled and passes validation method.
                    continue;
                }
            } else {
                // If a field is not required and not filled, no validation should be done.
                continue;
            }

            // If no continue was called we have a failed validation.
            if ($messageArray) {
                $errors[$field][] = $message[$validationIndex] ?? $field;
            } else {
                $errors[$field] = $message;
            }
        }
    }

    return $errors;
}

/**
 * Creates a script tag to load a javascript file
 *
 * @param string|array $url
 * @param string|array $options
 * @return string|null
 */
function js($url, $options = null): ?string
{
    if (is_array($url) === true) {
        $scripts = array_map(function ($url) use ($options) {
            return js($url, $options);
        }, $url);

        return implode(PHP_EOL, $scripts);
    }

    if (is_bool($options) === true) {
        $options = ['async' => $options];
    }

    $kirby = App::instance();

    if ($url === '@auto') {
        if (!$url = Url::toTemplateAsset('js/templates', 'js')) {
            return null;
        }
    }

    $url  = ($kirby->component('js'))($kirby, $url, $options);
    $url  = Url::to($url);
    $attr = array_merge((array)$options, ['src' => $url]);

    return '<script ' . attr($attr) . '></script>';
}

/**
 * Returns the Kirby object in any situation
 *
 * @return \Kirby\Cms\App
 */
function kirby()
{
    return App::instance();
}

/**
 * Makes it possible to use any defined Kirbytag as standalone function
 *
 * @param string|array $type
 * @param string|null $value
 * @param array $attr
 * @param array $data
 * @return string
 */
function kirbytag($type, ?string $value = null, array $attr = [], array $data = []): string
{
    if (is_array($type) === true) {
        $kirbytag = $type;
        $type     = key($kirbytag);
        $value    = current($kirbytag);
        $attr     = $kirbytag;

        // check data attribute and separate from attr data if exists
        if (isset($attr['data']) === true) {
            $data = $attr['data'];
            unset($attr['data']);
        }
    }

    return App::instance()->kirbytag($type, $value, $attr, $data);
}

/**
 * Parses KirbyTags in the given string. Shortcut
 * for `$kirby->kirbytags($text, $data)`
 *
 * @param string|null $text
 * @param array $data
 * @return string
 */
function kirbytags(?string $text = null, array $data = []): string
{
    return App::instance()->kirbytags($text, $data);
}

/**
 * Parses KirbyTags and Markdown in the
 * given string. Shortcut for `$kirby->kirbytext()`
 *
 * @param string|null $text
 * @param array $data
 * @return string
 */
function kirbytext(?string $text = null, array $data = []): string
{
    return App::instance()->kirbytext($text, $data);
}

/**
 * Parses KirbyTags and inline Markdown in the
 * given string.
 * @since 3.1.0
 *
 * @param string|null $text
 * @param array $data
 * @return string
 */
function kirbytextinline(?string $text = null, array $data = []): string
{
    return App::instance()->kirbytext($text, $data, true);
}

/**
 * Shortcut for `kirbytext()` helper
 *
 * @param string|null $text
 * @param array $data
 * @return string
 */
function kt(?string $text = null, array $data = []): string
{
    return kirbytext($text, $data);
}

/**
 * Shortcut for `kirbytextinline()` helper
 * @since 3.1.0
 *
 * @param string|null $text
 * @param array $data
 * @return string
 */
function kti(?string $text = null, array $data = []): string
{
    return kirbytextinline($text, $data);
}

/**
 * A super simple class autoloader
 *
 * @param array $classmap
 * @param string|null $base
 * @return void
 */
function load(array $classmap, ?string $base = null)
{
    // convert all classnames to lowercase
    $classmap = array_change_key_case($classmap);

    spl_autoload_register(function ($class) use ($classmap, $base) {
        $class = strtolower($class);

        if (!isset($classmap[$class])) {
            return false;
        }

        if ($base) {
            include $base . '/' . $classmap[$class];
        } else {
            include $classmap[$class];
        }
    });
}

/**
 * Parses markdown in the given string. Shortcut for
 * `$kirby->markdown($text)`
 *
 * @param string|null $text
 * @return string
 */
function markdown(?string $text = null): string
{
    return App::instance()->markdown($text);
}

/**
 * Shortcut for `$kirby->option($key, $default)`
 *
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function option(string $key, $default = null)
{
    return App::instance()->option($key, $default);
}

/**
 * Fetches a single page or multiple pages by
 * id or the current page when no id is specified
 *
 * @param string|array ...$id
 * @return \Kirby\Cms\Page|\Kirby\Cms\Pages|null
 * @todo reduce to one parameter in 3.7.0 (also change return and return type)
 */
function page(...$id)
{
    if (empty($id) === true) {
        return App::instance()->site()->page();
    }

    if (count($id) > 1) {
        // @codeCoverageIgnoreStart
        deprecated('Passing multiple parameters to the `page()` helper has been deprecated. Please use the `pages()` helper instead.');
        // @codeCoverageIgnoreEnd
    }

    return App::instance()->site()->find(...$id);
}

/**
 * Helper to build page collections
 *
 * @param string|array ...$id
 * @return \Kirby\Cms\Page|\Kirby\Cms\Pages|null
 * @todo return only Pages|null in 3.7.0, wrap in Pages for single passed id
 */
function pages(...$id)
{
    if (count($id) === 1) {
        // @codeCoverageIgnoreStart
        deprecated('Passing a single id to the `pages()` helper will return a Kirby\Cms\Pages collection with a single element instead of the single Kirby\Cms\Page object itself - starting in 3.7.0.');
        // @codeCoverageIgnoreEnd
    }

    return App::instance()->site()->find(...$id);
}

/**
 * Returns a single param from the URL
 *
 * @param string $key
 * @param string|null $fallback
 * @return string|null
 */
function param(string $key, ?string $fallback = null): ?string
{
    return App::instance()->request()->url()->params()->$key ?? $fallback;
}

/**
 * Returns all params from the current Url
 *
 * @return array
 */
function params(): array
{
    return App::instance()->request()->url()->params()->toArray();
}

/**
 * Smart version of return with an if condition as first argument
 *
 * @param mixed $condition
 * @param mixed $value The string to be returned if the condition is true
 * @param mixed $alternative An alternative string which should be returned when the condition is false
 * @return mixed
 */
function r($condition, $value, $alternative = null)
{
    return $condition ? $value : $alternative;
}

/**
 * Creates a micro-router and executes
 * the routing action immediately
 *
 * @param string|null $path
 * @param string $method
 * @param array $routes
 * @param \Closure|null $callback
 * @return mixed
 */
function router(?string $path = null, string $method = 'GET', array $routes = [], ?Closure $callback = null)
{
    return (new Router($routes))->call($path, $method, $callback);
}

/**
 * Returns the current site object
 *
 * @return \Kirby\Cms\Site
 */
function site()
{
    return App::instance()->site();
}

/**
 * Determines the size/length of numbers, strings, arrays and countable objects
 *
 * @param mixed $value
 * @return int
 * @throws \Kirby\Exception\InvalidArgumentException
 */
function size($value): int
{
    if (is_numeric($value)) {
        return (int)$value;
    }

    if (is_string($value)) {
        return Str::length(trim($value));
    }

    if (is_array($value)) {
        return count($value);
    }

    if (is_object($value)) {
        if (is_a($value, 'Countable') === true) {
            return count($value);
        }

        if (is_a($value, 'Kirby\Toolkit\Collection') === true) {
            return $value->count();
        }
    }

    throw new InvalidArgumentException('Could not determine the size of the given value');
}

/**
 * Enhances the given string with
 * smartypants. Shortcut for `$kirby->smartypants($text)`
 *
 * @param string|null $text
 * @return string
 */
function smartypants(?string $text = null): string
{
    return App::instance()->smartypants($text);
}

/**
 * Embeds a snippet from the snippet folder
 *
 * @param string|array $name
 * @param array|object $data
 * @param bool $return
 * @return string
 */
function snippet($name, $data = [], bool $return = false)
{
    if (is_object($data) === true) {
        $data = ['item' => $data];
    }

    $snippet = App::instance()->snippet($name, $data);

    if ($return === true) {
        return $snippet;
    }

    echo $snippet;
}

/**
 * Includes an SVG file by absolute or
 * relative file path.
 *
 * @param string|\Kirby\Cms\File $file
 * @return string|false
 */
function svg($file)
{
    // support for Kirby's file objects
    if (is_a($file, 'Kirby\Cms\File') === true && $file->extension() === 'svg') {
        return $file->read();
    }

    if (is_string($file) === false) {
        return false;
    }

    $extension = F::extension($file);

    // check for valid svg files
    if ($extension !== 'svg') {
        return false;
    }

    // try to convert relative paths to absolute
    if (file_exists($file) === false) {
        $root = App::instance()->root();
        $file = realpath($root . '/' . $file);
    }

    return F::read($file);
}

/**
 * Returns translate string for key from translation file
 *
 * @param string|array $key
 * @param string|null $fallback
 * @return array|string|null
 */
function t($key, string $fallback = null)
{
    return I18n::translate($key, $fallback);
}

/**
 * Translates a count
 *
 * @param string $key
 * @param int $count
 * @return mixed
 */
function tc(string $key, int $count)
{
    return I18n::translateCount($key, $count);
}

/**
 * Rounds the minutes of the given date
 * by the defined step
 *
 * @param string|null $date
 * @param int|array|null $step array of `unit` and `size` to round to nearest
 * @return int|null
 */
function timestamp(?string $date = null, $step = null): ?int
{
    if (V::date($date) === false) {
        return null;
    }

    $date = strtotime($date);

    if ($step === null) {
        return $date;
    }

    // fallback for pre-3.5.0 usage
    if (is_int($step) === true) {
        $step = [
            'unit' => 'minute',
            'size' => $step
        ];
    }

    if (is_array($step) === false) {
        return $date;
    }

    $parts = [
        'second' => date('s', $date),
        'minute' => date('i', $date),
        'hour'   => date('H', $date),
        'day'    => date('d', $date),
        'month'  => date('m', $date),
        'year'   => date('Y', $date),
    ];

    $current = $parts[$step['unit']];
    $nearest = round($current / $step['size']) * $step['size'];
    $parts[$step['unit']] = $nearest;

    foreach ($parts as $part => $value) {
        if ($part === $step['unit']) {
            break;
        }

        $parts[$part] = 0;
    }

    $timestamp = strtotime(
        $parts['year'] . '-' .
        str_pad($parts['month'], 2, 0, STR_PAD_LEFT) . '-' .
        str_pad($parts['day'], 2, 0, STR_PAD_LEFT) . ' ' .
        str_pad($parts['hour'], 2, 0, STR_PAD_LEFT) . ':' .
        str_pad($parts['minute'], 2, 0, STR_PAD_LEFT) . ':' .
        str_pad($parts['second'], 2, 0, STR_PAD_LEFT)
    );

    // on error, convert `false` into `null`
    return $timestamp ?? null;
}

/**
 * Translate by key and then replace
 * placeholders in the text
 *
 * @param string $key
 * @param string|array|null $fallback
 * @param array|null $replace
 * @param string|null $locale
 * @return string
 */
function tt(string $key, $fallback = null, ?array $replace = null, ?string $locale = null)
{
    return I18n::template($key, $fallback, $replace, $locale);
}

/**
 * Builds a Twitter link
 *
 * @param string $username
 * @param string|null $text
 * @param string|null $title
 * @param string|null $class
 * @return string
 */
function twitter(string $username, ?string $text = null, ?string $title = null, ?string $class = null): string
{
    return kirbytag([
        'twitter' => $username,
        'text'    => $text,
        'title'   => $title,
        'class'   => $class
    ]);
}

/**
 * Shortcut for url()
 *
 * @param string|null $path
 * @param array|string|null $options
 * @return string
 */
function u(?string $path = null, $options = null): string
{
    return Url::to($path, $options);
}

/**
 * Builds an absolute URL for a given path
 *
 * @param string|null $path
 * @param array|string|null $options
 * @return string
 */
function url(?string $path = null, $options = null): string
{
    return Url::to($path, $options);
}

/**
 * Creates a compliant v4 UUID
 * Taken from: https://github.com/symfony/polyfill
 *
 * @return string
 */
function uuid(): string
{
    $uuid = bin2hex(random_bytes(16));

    return sprintf(
        '%08s-%04s-4%03s-%04x-%012s',
        // 32 bits for "time_low"
        substr($uuid, 0, 8),
        // 16 bits for "time_mid"
        substr($uuid, 8, 4),
        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        substr($uuid, 13, 3),
        // 16 bits:
        // * 8 bits for "clk_seq_hi_res",
        // * 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        hexdec(substr($uuid, 16, 4)) & 0x3fff | 0x8000,
        // 48 bits for "node"
        substr($uuid, 20, 12)
    );
}


/**
 * Creates a video embed via iframe for Youtube or Vimeo
 * videos. The embed Urls are automatically detected from
 * the given Url.
 *
 * @param string $url
 * @param array $options
 * @param array $attr
 * @return string|null
 */
function video(string $url, array $options = [], array $attr = []): ?string
{
    return Html::video($url, $options, $attr);
}

/**
 * Embeds a Vimeo video by URL in an iframe
 *
 * @param string $url
 * @param array $options
 * @param array $attr
 * @return string|null
 */
function vimeo(string $url, array $options = [], array $attr = []): ?string
{
    return Html::vimeo($url, $options, $attr);
}

/**
 * The widont function makes sure that there are no
 * typographical widows at the end of a paragraph –
 * that's a single word in the last line
 *
 * @param string|null $string
 * @return string
 */
function widont(string $string = null): string
{
    return Str::widont($string);
}

/**
 * Embeds a Youtube video by URL in an iframe
 *
 * @param string $url
 * @param array $options
 * @param array $attr
 * @return string|null
 */
function youtube(string $url, array $options = [], array $attr = []): ?string
{
    return Html::youtube($url, $options, $attr);
}
