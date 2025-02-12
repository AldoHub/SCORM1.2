<?php

use Kirby\Panel\Panel;

return function ($kirby) {
    return [
        'icon'  => 'user',
        'label' => t('login'),
        'views' => [
            [
                'pattern' => 'login',
                'auth'    => false,
                'action'  => function () use ($kirby) {
                    $system = $kirby->system();
                    $status = $kirby->auth()->status();
                    return [
                        'component' => 'k-login-view',
                        'props'     => [
                            'methods' => array_keys($system->loginMethods()),
                            'pending' => [
                                'email'     => $status->email(),
                                'challenge' => $status->challenge()
                            ]
                        ],
                    ];
                }
            ],
            [
                'pattern' => '(:all)',
                'auth'    => false,
                'action'  => function ($path) use ($kirby) {
                    /**
                     * Store the current path in the session
                     * Once the user is logged in, the path will
                     * be used to redirect to that view again
                     */
                    $kirby->session()->set('panel.path', $path);
                    Panel::go('login');
                }
            ]
        ]
    ];
};
