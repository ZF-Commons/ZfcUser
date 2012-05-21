<?php
return array(
    'zfcuser' => array(
        'user_model_class'          => 'ZfcUser\Model\User',
        'usermeta_model_class'      => 'ZfcUser\Model\UserMeta',
        'enable_registration'       => true,
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
        // We _could_ use a factory to create the controller
        'factories' => array(
            'zfcuser' => 'ZfcUser\Service\ControllerFactory',
        ),
        // Below is a plugin map for the controller plugin broker
        'map' => array(
            'zfcuserauthentication' => 'ZfcUser\Controller\Plugin\ZfcUserAuthentication',
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

    'di' => array(
        'instance' => array(
            'alias' => array(
                'zfcuser_uemail_validator'         => 'ZfcUser\Validator\NoRecordExists',
                'zfcuser_uusername_validator'      => 'ZfcUser\Validator\NoRecordExists',
                'zfcuser_captcha_element'          => 'Zend\Form\Element\Captcha',
                // Default Zend\Db
                'zfcuser_zend_db_adapter' => 'Zend\Db\Adapter\Adapter',
            ),
            'zfcuser_captcha_element' => array(
                'parameters' => array(
                    'spec' => 'captcha',
                    'options'=>array(
                        'label'      => 'Please enter the 5 letters displayed below:',
                        'required'   => true,
                        'captcha'    => array(
                            'captcha' => 'Figlet',
                            'wordlen' => 5,
                            'timeout '=> 300,
                        ),
                        'order'      => 500,
                    ),
                ),
            ),
            'ZfcUser\Form\Register' => array(
                'parameters' => array(
                    'captcha_element'   => 'zfcuser_captcha_element'
                ),
            ),
            'zfcuser_uemail_validator' => array(
                'parameters' => array(
                    'mapper'  => 'zfcuser_user_mapper',
                    'options' => array(
                        'key' => 'email',
                    ),
                ),
            ),
            'zfcuser_uusername_validator' => array(
                'parameters' => array(
                    'mapper'  => 'zfcuser_user_mapper',
                    'options' => array(
                        'key' => 'username',
                    ),
                ),
            ),
            'ZfcUser\View\Helper\ZfcUserLoginWidget' => array(
                'parameters' => array(
                    'loginForm'      => 'ZfcUser\Form\Login',
                ),
            ),
            'ZfcUser\Form\RegisterFilter' => array(
                'parameters' => array(
                    'emailValidator'    => 'zfcuser_uemail_validator',
                    'usernameValidator' => 'zfcuser_uusername_validator',
                ),
            ),
        ),
    ),
);
