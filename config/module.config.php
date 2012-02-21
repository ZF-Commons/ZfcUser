<?php
return array(
    'zfcuser' => array(
        'user_model_class'          => 'ZfcUser\Model\UserBase',
        'usermeta_model_class'      => 'ZfcUser\Model\UserMetaBase',
        'enable_username'           => false,
        'enable_display_name'       => false,
        'require_activation'        => false,
        'login_after_registration'  => true,
        'registration_form_captcha' => true,
        'password_hash_algorithm'   => 'blowfish', // blowfish, sha512, sha256
        'blowfish_cost'             => 10,         // integer between 4 and 31
        'sha256_rounds'             => 5000,       // integer between 1000 and 999,999,999
        'sha512_counds'             => 5000,       // integer between 1000 and 999,999,999
    ),
    'routes' => array(
    ),
    'di' => array(
        'instance' => array(
            'alias' => array(
                'zfcuser'                          => 'ZfcUser\Controller\UserController',
                'zfcuser_user_service'             => 'ZfcUser\Service\User',
                'zfcuser_auth_service'             => 'Zend\Authentication\AuthenticationService',
                'zfcuser_uemail_validator'         => 'ZfcUser\Validator\NoRecordExists',
                'zfcuser_uusername_validator'      => 'ZfcUser\Validator\NoRecordExists',
                'zfcuser_captcha_element'          => 'Zend\Form\Element\Captcha',

                // Default Zend\Db
                'zfcuser_write_db'        => 'Zend\Db\Adapter\DiPdoMysql',
                'zfcuser_read_db'         => 'zfcuser_write_db',
                'zfcuser_user_mapper'     => 'ZfcUser\Model\Mapper\UserZendDb',
                'zfcuser_usermeta_mapper' => 'ZfcUser\Model\Mapper\UserMetaZendDb',
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
            'zfcuser' => array(
                'parameters' => array(
                    'loginForm'    => 'ZfcUser\Form\Login',
                    'registerForm' => 'ZfcUser\Form\Register',
                    'userService'  => 'ZfcUser\Service\User',
                ),
            ),
            'Zend\View\Resolver\TemplatePathStack' => array(
                'parameters' => array(
                    'paths'  => array(
                        'zfcuser' => __DIR__ . '/../view',
                    ),
                ),
            ),
            'Zend\Mvc\Controller\PluginLoader' => array(
                'parameters' => array(
                    'map' => array(
                        'zfcUserAuthentication' => 'ZfcUser\Controller\Plugin\ZfcUserAuthentication',
                    ),
                ),
            ),
            'ZfcUser\Controller\Plugin\ZfcUserAuthentication' => array(
                'parameters' => array(
                    'authAdapter' => 'ZfcUser\Authentication\Adapter\AdapterChain',
                    'authService' => 'zfcuser_auth_service',
                ),
            ),
            'ZfcUser\Authentication\Adapter\AdapterChain' => array(
                'parameters' => array(
                    'defaultAdapter' => 'ZfcUser\Authentication\Adapter\Db',
                ),
            ),
            'ZfcUser\Authentication\Adapter\Db' => array(
                'parameters' => array(
                    'mapper' => 'zfcuser_user_mapper',
                ),
            ),
            'zfcuser_auth_service' => array(
                'parameters' => array(
                    'storage' => 'ZfcUser\Authentication\Storage\Db',
                ),
            ),
            'ZfcUser\Authentication\Storage\Db' => array(
                'parameters' => array(
                    'mapper' => 'zfcuser_user_mapper',
                ),
            ),
            'ZfcUser\Service\User' => array(
                'parameters' => array(
                    'authService'    => 'zfcuser_auth_service',
                    'userMapper'     => 'zfcuser_user_mapper',
                    'userMetaMapper' => 'zfcuser_usermeta_mapper',
                ),
            ),
            'ZfcUser\Form\Register' => array(
                'parameters' => array(
                    'emailValidator'    => 'zfcuser_uemail_validator',
                    'usernameValidator' => 'zfcuser_uusername_validator',
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

            /**
             * Mapper / DB
             */
            'zfcuser_write_db' => array(
                'parameters' => array(
                    'pdo'    => 'zfcuser_pdo',
                    'config' => array(),
                ),
            ),
            'ZfcUser\Model\Mapper\UserZendDb' => array(
                'parameters' => array(
                    'readAdapter'  => 'zfcuser_read_db',
                    'writeAdapter' => 'zfcuser_write_db',
                ),
            ),
            'ZfcUser\Model\Mapper\UserMetaZendDb' => array(
                'parameters' => array(
                    'readAdapter'  => 'zfcuser_read_db',
                    'writeAdapter' => 'zfcuser_write_db',
                ),
            ),

            /**
             * View helper(s)
             */
            'Zend\View\HelperLoader' => array(
                'parameters' => array(
                    'map' => array(
                        'zfcUserIdentity' => 'ZfcUser\View\Helper\ZfcUserIdentity',
                    ),
                ),
            ),
            'ZfcUser\View\Helper\ZfcUserIdentity' => array(
                'parameters' => array(
                    'authService' => 'zfcuser_auth_service',
                ),
            ),

            /**
             * Routes
             */

            'Zend\Mvc\Router\RouteStack' => array(
                'parameters' => array(
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
            ),
        ),
    ),
);
