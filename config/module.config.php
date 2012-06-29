<?php
return array(
    'zfcuser' => array(
        //'user_entity_class'                 => 'ZfcUser\Entity\User',
        //'enable_registration'               => true,
        //NOTE: Please override the setting below via your zfcuser.global.php file
        //      Uncommenting the line below will break any overrides in later config files
        //      due to the way config file merging works with array values
        //'use_redirect_parameter_if_present' => false,
        //'auth_identity_fields'              => array( 'email' ),
        //'enable_username'                   => false,
        //'enable_display_name'               => false,
        //'login_form_timeout'                => 300,
        //'user_form_timeout'                 => 300,
        //'login_after_registration'          => true,
        //'use_registration_form_captcha'     => true,
        //'password_cost'                     => 10,         // integer between 4 and 31
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'zfcuser' => __DIR__ . '/../view',
        ),
        //'helper_map' => array(
        //    'zfcUserIdentity'    => 'ZfcUser\View\Helper\ZfcUserIdentity',
        //    'zfcUserLoginWidget' => 'ZfcUser\View\Helper\ZfcUserLoginWidget',
        //),
        //'strategies' => array(
        //    'ViewJsonStrategy',
        //),
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
