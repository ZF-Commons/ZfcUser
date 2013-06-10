<?php

return array(
    'zfc_user' => array(
        'type' => 'literal',
        'options' => array(
            'route'    => '/user',
            'defaults' => array(
                'controller' => 'ZfcUser\Controller\UserController',
                'action'     => 'index',
            ),
        ),
        'may_terminate' => true,
        'child_routes' => array(
            'endpoint' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/endpoint',
                    'defaults' => array(
                        'controller' => 'ZfcUser\Controller\UserController',
                        'action'     => 'endpoint'
                    )
                )
            ),
            'forgot' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/forgot',
                    'defaults' => array(
                        'controller' => 'ZfcUser\Controller\UserController',
                        'action'     => 'forgot'
                    )
                )
            ),
            'login' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/login',
                    'defaults' => array(
                        'controller' => 'ZfcUser\Controller\UserController',
                        'action'     => 'login'
                    )
                )
            ),
            'logout' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/logout',
                    'defaults' => array(
                        'controller' => 'ZfcUser\Controller\UserController',
                        'action'     => 'logout'
                    )
                )
            ),
            'social' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/social/:provider',
                    'constraints' => array(
                        'provider' => '[a-zA-Z]+',
                    ),
                    'defaults' => array(
                        'controller' => 'ZfcUser\Controller\UserController',
                        'action'     => 'social'
                    )
                )
            ),
            'register' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/register',
                    'defaults' => array(
                        'controller' => 'ZfcUser\Controller\UserController',
                        'action'     => 'register'
                    )
                )
            )
        )
    )
);