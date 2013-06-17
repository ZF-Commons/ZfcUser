<?php

return array(
    'routes' => array(
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
                'login' => array(
                    'type' => 'literal',
                    'options' => array(
                        'route' => '/login',
                        'defaults' => array(
                            'controller' => 'ZfcUser\Controller\LoginController',
                            'action'     => 'login'
                        )
                    )
                ),
                'logout' => array(
                    'type' => 'literal',
                    'options' => array(
                        'route' => '/logout',
                        'defaults' => array(
                            'controller' => 'ZfcUser\Controller\LoginController',
                            'action'     => 'logout'
                        )
                    )
                ),
                'register' => array(
                    'type' => 'literal',
                    'options' => array(
                        'route' => '/register',
                        'defaults' => array(
                            'controller' => 'ZfcUser\Controller\RegisterController',
                            'action'     => 'register'
                        )
                    )
                )
            )
        )
    )
);