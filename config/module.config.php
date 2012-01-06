<?php
return array(
    'edpuser' => array(
        'user_model_class'          => 'EdpUser\Model\User',
        'usermeta_model_class'      => 'EdpUser\Model\UserMeta',
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
        'edpuser' => array(
            'type' => 'Literal',
            'priority' => 1000,
            'options' => array(
                'route' => '/user',
                'defaults' => array(
                    'controller' => 'edpuser',
                ),
            ),
            'may_terminate' => true,
            'child_routes' => array(
                'login' => array(
                    'type' => 'Literal',
                    'options' => array(
                        'route' => '/login',
                        'defaults' => array(
                            'controller' => 'edpuser',
                            'action'     => 'login',
                        ),
                    ),
                ),
                'authenticate' => array(
                    'type' => 'Literal',
                    'options' => array(
                        'route' => '/authenticate',
                        'defaults' => array(
                            'controller' => 'edpuser',
                            'action'     => 'authenticate',
                        ),
                    ),
                ),
                'logout' => array(
                    'type' => 'Literal',
                    'options' => array(
                        'route' => '/logout',
                        'defaults' => array(
                            'controller' => 'edpuser',
                            'action'     => 'logout',
                        ),
                    ),
                ),
                'register' => array(
                    'type' => 'Literal',
                    'options' => array(
                        'route' => '/register',
                        'defaults' => array(
                            'controller' => 'edpuser',
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
                'edpuser'                          => 'EdpUser\Controller\UserController',
                'edpuser_user_mapper'              => 'EdpUser\Mapper\UserZendDb',
                'edpuser_usermeta_mapper'          => 'EdpUser\Mapper\UserMetaZendDb',
                'edpuser_user_service'             => 'EdpUser\Service\User',
                'edpuser_write_db'                 => 'Zend\Db\Adapter\DiPdoMysql',
                'edpuser_read_db'                  => 'edpuser_write_db',
                'edpuser_doctrine_em'              => 'doctrine_em',
                'edpuser_auth_service'             => 'Zend\Authentication\AuthenticationService',
                'edpuser_controller_plugin_broker' => 'Zend\Mvc\Controller\PluginBroker',
                'edpuser_controller_plugin_loader' => 'Zend\Mvc\Controller\PluginLoader',
            ),
            'edpuser' => array(
                'parameters' => array(
                    'loginForm'    => 'EdpUser\Form\Login',
                    'registerForm' => 'EdpUser\Form\Register',
                    'userService'  => 'EdpUser\Service\User',
                    'broker'       => 'edpuser_controller_plugin_broker',
                ),
            ),
            'edpuser_controller_plugin_broker' => array(
                'parameters' => array(
                    'loader' => 'edpuser_controller_plugin_loader',
                ),
            ),
            'edpuser_controller_plugin_loader' => array(
                'parameters' => array(
                    'map' => array(
                        'edpUserAuthentication' => 'EdpUser\Controller\Plugin\EdpUserAuthentication',
                    ),
                ),
            ),
            'EdpUser\Controller\Plugin\EdpUserAuthentication' => array(
                'parameters' => array(
                    'authAdapter' => 'EdpUser\Authentication\Adapter\AdapterChain',
                    'authService' => 'edpuser_auth_service',
                ),
            ),
            'EdpUser\Authentication\Adapter\AdapterChain' => array(
                'parameters' => array(
                    'defaultAdapter' => 'EdpUser\Authentication\Adapter\Db',
                ),
            ),
            'EdpUser\Authentication\Adapter\Db' => array(
                'parameters' => array(
                    'mapper' => 'edpuser_user_mapper',
                ),
            ),
            'edpuser_auth_service' => array(
                'parameters' => array(
                    'storage' => 'EdpUser\Authentication\Storage\Db',
                ),
            ),
            'EdpUser\Authentication\Storage\Db' => array(
                'parameters' => array(
                    'mapper' => 'edpuser_user_mapper',
                ),
            ),
            'EdpUser\Service\User' => array(
                'parameters' => array(
                    'authService'    => 'edpuser_auth_service',
                    'userMapper'     => 'edpuser_user_mapper',
                    'userMetaMapper' => 'edpuser_usermeta_mapper',
                ),
            ),
            'EdpUser\Form\Register' => array(
                'parameters' => array(
                    'userMapper' => 'edpuser_user_mapper',
                ),
            ),

            /**
             * Mapper / DB
             */

            'edpuser_write_db' => array(
                'parameters' => array(
                    'pdo'    => 'edpuser_pdo',
                    'config' => array(),
                ),
            ),
            'mongo_driver_chain' => array(
                'parameters' => array(
                    'drivers' => array(
                        'edpuser_annotation_driver' => array(
                            'class'     => 'Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver',
                            'namespace' => 'EdpUser\Document',
                            'paths'     => array(__DIR__ . '/src/EdpUser/Document')
                        ),
                        'edpuserbase_annotation_driver' => array(
                            'class'     => 'Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver',
                            'namespace' => 'EdpUser\ModelBase',
                            'paths'     => array(__DIR__ . '/src/EdpUser/ModelBase')
                        ),
                        'edpuserbase_xml_driver' => array(
                            'class'          => 'Doctrine\ODM\MongoDB\Mapping\Driver\XmlDriver',
                            'namespace'      => 'EdpUser\ModelBase',
                            'paths'          => array(__DIR__ . '/xml'),
                            'file_extension' => '.mongodb.xml',
                        ),
                    )
                )
            ),
            'orm_driver_chain' => array(
                'parameters' => array(
                    'drivers' => array(
                        'edpuser_xml_driver' => array(
                            'class'     => 'Doctrine\ORM\Mapping\Driver\XmlDriver',
                            'namespace' => 'EdpUser\Entity',
                            'paths'     => array(__DIR__ . '/xml'),
                        ),
                    ),
                )
            ),
            'EdpUser\Mapper\UserDoctrine' => array(
                'parameters' => array(
                    'em' => 'edpuser_doctrine_em',
                ),
            ),
            'EdpUser\Mapper\UserZendDb' => array(
                'parameters' => array(
                    'readAdapter'  => 'edpuser_read_db',
                    'writeAdapter' => 'edpuser_write_db',
                ),
            ),
            'EdpUser\Mapper\UserMetaDoctrine' => array(
                'parameters' => array(
                    'em' => 'edpuser_doctrine_em',
                ),
            ),
            'EdpUser\Mapper\UserMetaZendDb' => array(
                'parameters' => array(
                    'readAdapter'  => 'edpuser_read_db',
                    'writeAdapter' => 'edpuser_write_db',
                ),
            ),
            
            /**
             * View helper(s)
             */

            'Zend\View\PhpRenderer' => array(
                'parameters' => array(
                    'options'  => array(
                        'script_paths' => array(
                            'edpuser' => __DIR__ . '/../views',
                        ),
                    ),
                    'broker' => 'Zend\View\HelperBroker',
                ),
            ),
            'Zend\View\HelperLoader' => array(
                'parameters' => array(
                    'map' => array(
                        'edpUserIdentity' => 'EdpUser\View\Helper\EdpUserIdentity',
                    ),
                ),
            ),
            'Zend\View\HelperBroker' => array(
                'parameters' => array(
                    'loader' => 'Zend\View\HelperLoader',
                ),
            ),
            'EdpUser\View\Helper\EdpUserIdentity' => array(
                'parameters' => array(
                    'authService' => 'edpuser_auth_service',
                ),
            ),
        ),
    ),
);
