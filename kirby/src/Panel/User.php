<?php

namespace Kirby\Panel;

/**
 * Provides information about the user model for the Panel
 * @since 3.6.0
 *
 * @package   Kirby Panel
 * @author    Nico Hoffmann <nico@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier GmbH
 * @license   https://getkirby.com/license
 */
class User extends Model
{
    /**
     * Breadcrumb array
     *
     * @return array
     */
    public function breadcrumb(): array
    {
        return [
            [
                'label' => $this->model->username(),
                'link'  => $this->url(true),
            ]
        ];
    }

    /**
     * Provides options for the user dropdown
     *
     * @param array $options
     * @return array
     */
    public function dropdown(array $options = []): array
    {
        $page        = $this->model;
        $permissions = $this->options(['preview']);
        $url         = $this->url(true);
        $result      = [];

        $result[] = [
            'dialog'   => $url . '/changeName',
            'icon'     => 'title',
            'text'     => t('user.changeName'),
            'disabled' => $this->isDisabledDropdownOption('changeName', $options, $permissions)
        ];

        $result[] = [
            'dialog'   => $url . '/changeEmail',
            'icon'     => 'email',
            'text'     => t('user.changeEmail'),
            'disabled' => $this->isDisabledDropdownOption('changeEmail', $options, $permissions)
        ];

        $result[] = [
            'dialog'   => $url . '/changeRole',
            'icon'     => 'bolt',
            'text'     => t('user.changeRole'),
            'disabled' => $this->isDisabledDropdownOption('changeRole', $options, $permissions)
        ];

        $result[] = [
            'dialog'   => $url . '/changePassword',
            'icon'     => 'key',
            'text'     => t('user.changePassword'),
            'disabled' => $this->isDisabledDropdownOption('changePassword', $options, $permissions)
        ];

        $result[] = [
            'dialog'   => $url . '/changeLanguage',
            'icon'     => 'globe',
            'text'     => t('user.changeLanguage'),
            'disabled' => $this->isDisabledDropdownOption('changeLanguage', $options, $permissions)
        ];

        $result[] = [
            'dialog'   => $url . '/delete',
            'icon'     => 'trash',
            'text'     => t('user.delete'),
            'disabled' => $this->isDisabledDropdownOption('delete', $options, $permissions)
        ];

        return $result;
    }

    /**
     * Default settings for the user's Panel image
     *
     * @return array
     */
    protected function imageDefaults(): array
    {
        return array_merge(parent::imageDefaults(), [
            'back'  => 'black',
            'icon'  => 'user',
            'ratio' => '1/1',
        ]);
    }

    /**
     * Returns the image file object based on provided query
     *
     * @param string|null $query
     * @return \Kirby\Cms\File|\Kirby\Filesystem\Asset|null
     */
    protected function imageSource(string $query = null)
    {
        if ($query === null) {
            return $this->model->avatar();
        }

        return parent::imageSource($query);
    }

    /**
     * Returns the full path without leading slash
     *
     * @return string
     */
    public function path(): string
    {
        return 'users/' . $this->model->id();
    }

    /**
     * Returns prepared data for the panel user picker
     *
     * @param array|null $params
     * @return array
     */
    public function pickerData(array $params = null): array
    {
        $params['text'] = $params['text'] ?? '{{ user.username }}';

        return array_merge(parent::pickerData($params), [
            'email'    => $this->model->email(),
            'username' => $this->model->username(),
        ]);
    }

    /**
     * Returns navigation array with
     * previous and next user
     *
     * @internal
     *
     * @return array
     */
    public function prevNext(): array
    {
        $user = $this->model;

        return [
            'next' => function () use ($user) {
                $next = $user->next();
                return $next ? $next->panel()->toLink('username') : null;
            },
            'prev' => function () use ($user) {
                $prev = $user->prev();
                return $prev ? $prev->panel()->toLink('username') : null;
            }
        ];
    }

    /**
     * Returns the data array for the
     * view's component props
     *
     * @internal
     *
     * @return array
     */
    public function props(): array
    {
        $user   = $this->model;
        $avatar = $user->avatar();

        return array_merge(
            parent::props(),
            $this->prevNext(),
            [
                'blueprint' => $this->model->role()->name(),
                'model' => [
                    'avatar'   => $avatar ? $avatar->url() : null,
                    'content'  => $this->content(),
                    'email'    => $user->email(),
                    'id'       => $user->id(),
                    'language' => $this->translation()->name(),
                    'link'     => $this->url(true),
                    'name'     => $user->name()->toString(),
                    'role'     => $user->role()->title(),
                    'username' => $user->username(),
                ]
            ]
        );
    }

    /**
     * Returns the data array for
     * this model's Panel routes
     *
     * @internal
     *
     * @return array
     */
    public function route(): array
    {
        return [
            'breadcrumb' => $this->breadcrumb(),
            'component'  => 'k-user-view',
            'props'      => $this->props(),
            'title'      => $this->model->username(),
        ];
    }

    /**
     * Returns the Translation object
     * for the selected Panel language
     *
     * @return \Kirby\Cms\Translation
     */
    public function translation()
    {
        $kirby = $this->model->kirby();
        $lang  = $this->model->language();
        return $kirby->translation($lang);
    }
}
