<?php
return array(
    'zfcuser' => array(
        'user_model_class'          => 'ZfcUser\Model\User',
        'usermeta_model_class'      => 'ZfcUser\Model\UserMeta',
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
        'zfcuser' => array(
            'type' => 'Literal',
            'priority' => 1000,
            'options' => array(
                'route' => '/user',
                'defaults' => array(
                    'controller' => 'zfcuser',
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
    'di' => array(
        'instance' => array(
            'alias' => array(
                'zfcuser'                          => 'ZfcUser\Controller\UserController',
                'zfcuser_user_mapper'              => 'ZfcUser\Mapper\UserZendDb',
                'zfcuser_usermeta_mapper'          => 'ZfcUser\Mapper\UserMetaZendDb',
                'zfcuser_user_service'             => 'ZfcUser\Service\User',
                'zfcuser_write_db'                 => 'Zend\Db\Adapter\DiPdoMysql',
                'zfcuser_read_db'                  => 'zfcuser_write_db',
                'zfcuser_doctrine_em'              => 'doctrine_em',
                'zfcuser_auth_service'             => 'Zend\Authentication\AuthenticationService',
                'zfcuser_controller_plugin_broker' => 'Zend\Mvc\Controller\PluginBroker',
                'zfcuser_controller_plugin_loader' => 'Zend\Mvc\Controller\PluginLoader',
            ),
            'zfcuser' => array(
                'parameters' => array(
                    'loginForm'    => 'ZfcUser\Form\Login',
                    'registerForm' => 'ZfcUser\Form\Register',
                    'userService'  => 'ZfcUser\Service\User',
                    'broker'       => 'zfcuser_controller_plugin_broker',
                ),
            ),
            'zfcuser_controller_plugin_broker' => array(
                'parameters' => array(
                    'loader' => 'zfcuser_controller_plugin_loader',
                ),
            ),
            'zfcuser_controller_plugin_loader' => array(
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
                    'userMapper' => 'zfcuser_user_mapper',
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
            'mongo_driver_chain' => array(
                'parameters' => array(
                    'drivers' => array(
                        'zfcuser_annotation_driver' => array(
                            'class'     => 'Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver',
                            'namespace' => 'ZfcUser\Document',
                            'paths'     => array(__DIR__ . '/src/ZfcUser/Document')
                        ),
                        'zfcuserbase_annotation_driver' => array(
                            'class'     => 'Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver',
                            'namespace' => 'ZfcUser\ModelBase',
                            'paths'     => array(__DIR__ . '/src/ZfcUser/ModelBase')
                        ),
                        'zfcuserbase_xml_driver' => array(
                            'class'          => 'Doctrine\ODM\MongoDB\Mapping\Driver\XmlDriver',
                            'namespace'      => 'ZfcUser\ModelBase',
                            'paths'          => array(__DIR__ . '/xml'),
                            'file_extension' => '.mongodb.xml',
                        ),
                    )
                )
            ),
            'orm_driver_chain' => array(
                'parameters' => array(
                    'drivers' => array(
                        'zfcuser_xml_driver' => array(
                            'class'     => 'Doctrine\ORM\Mapping\Driver\XmlDriver',
                            'namespace' => 'ZfcUser\Entity',
                            'paths'     => array(__DIR__ . '/xml'),
                        ),
                    ),
                )
            ),
            'ZfcUser\Mapper\UserDoctrine' => array(
                'parameters' => array(
                    'em' => 'zfcuser_doctrine_em',
                ),
            ),
            'ZfcUser\Mapper\UserZendDb' => array(
                'parameters' => array(
                    'readAdapter'  => 'zfcuser_read_db',
                    'writeAdapter' => 'zfcuser_write_db',
                ),
            ),
            'ZfcUser\Mapper\UserMetaDoctrine' => array(
                'parameters' => array(
                    'em' => 'zfcuser_doctrine_em',
                ),
            ),
            'ZfcUser\Mapper\UserMetaZendDb' => array(
                'parameters' => array(
                    'readAdapter'  => 'zfcuser_read_db',
                    'writeAdapter' => 'zfcuser_write_db',
                ),
            ),
            
            /**
             * View helper(s)
             */

            'Zend\View\PhpRenderer' => array(
                'parameters' => array(
                    'options'  => array(
                        'script_paths' => array(
                            'zfcuser' => __DIR__ . '/../views',
                        ),
                    ),
                    'broker' => 'Zend\View\HelperBroker',
                ),
            ),
            'Zend\View\HelperLoader' => array(
                'parameters' => array(
                    'map' => array(
                        'zfcUserIdentity' => 'ZfcUser\View\Helper\ZfcUserIdentity',
                    ),
                ),
            ),
            'Zend\View\HelperBroker' => array(
                'parameters' => array(
                    'loader' => 'Zend\View\HelperLoader',
                ),
            ),
            'ZfcUser\View\Helper\ZfcUserIdentity' => array(
                'parameters' => array(
                    'authService' => 'zfcuser_auth_service',
                ),
            ),
        ),
    ),
);
