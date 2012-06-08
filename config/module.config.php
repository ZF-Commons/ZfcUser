<?php
return array(
    'zfcuser' => array(
        'user_model_class'          => 'ZfcUser\Model\User',
        'usermeta_model_class'      => 'ZfcUser\Model\UserMeta',
        'enable_registration'       => true,
        'auth_identity_fields'      => array( 'email' ),
        'enable_username'           => false,
        'enable_display_name'       => false,
        'require_activation'        => false,
        'login_after_registration'  => true,
        'registration_form_captcha' => true,
        'password_hash_algorithm'   => 'blowfish', // blowfish, sha512, sha256
        'blowfish_cost'             => 10,         // integer between 4 and 31
        'sha256_rounds'             => 5000,       // integer between 1000 and 999,999,999
        'sha512_rounds'             => 5000,       // integer between 1000 and 999,999,999
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'zfcuser' => __DIR__ . '/../view',
        ),
        'helper_map' => array(
            'Zend\Form\View\HelperLoader',
            'zfcUserIdentity'        => 'ZfcUser\View\Helper\ZfcUserIdentity',
            'zfcUserLoginWidget'     => 'ZfcUser\View\Helper\ZfcUserLoginWidget',
        ),
    ),

    'controller' => array(
        'classes' => array(
            'zfcuser' => 'ZfcUser\Controller\UserController',
        ),
        'map' => array(
            'zfcuserauthentication' => 'ZfcUser\Controller\Plugin\ZfcUserAuthentication',
        ),
    ),

    'service_manager' => array(
        'aliases' => array(
            'zfcuser_zend_db_adapter' => 'Zend\Db\Adapter\Adapter',
        ),
    ),

    'router' => array(
        'routes' => array(
            'zfcuser' => array(
                'type' => 'Literal',
                'priority' => 1000,
                'options' => array(
                    'route' => '/user',
                    'defaults' => array(
                        'controller' => 'zfcuser',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'login' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/login',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'login',
                            ),
                        ),
                    ),
                    'authenticate' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/authenticate',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'authenticate',
                            ),
                        ),
                    ),
                    'logout' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/logout',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'logout',
                            ),
                        ),
                    ),
                    'register' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/register',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'register',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
