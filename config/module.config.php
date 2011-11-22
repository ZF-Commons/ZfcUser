<?php
return array(
    'edpuser' => array(
        'user_model_class'          => 'EdpUser\Model\User',
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
                'edpuser'               => 'EdpUser\Controller\UserController',
                'edpuser_register_form' => 'EdpUser\Form\Register',
                'edpuser_login_form'    => 'EdpUser\Form\Login',
                'edpuser_user_mapper'   => 'EdpUser\Mapper\UserDoctrine',
                'edpuser_user_service'  => 'EdpUser\Service\User',
                'edpuser_write_db'      => 'Zend\Db\Adapter\DiPdoMysql',
                'edpuser_read_db'       => 'edpuser_write_db',
                'edpuser_doctrine_em'   => 'doctrine_em',
            ),
            'edpuser_write_db' => array(
                'parameters' => array(
                    'pdo'    => 'edpuser_pdo',
                    'config' => array(),
                ),
            ),
            'doctrine_driver_chain' => array(
                'parameters' => array(
                    'drivers' => array(
                        'edpuserbase_annotationdriver' => array(
                            'class'           => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                            'namespace'       => 'EdpUser\ModelBase',
                            'paths'           => array(__DIR__ . '/../src/EdpUser/ModelBase'),
                        ),
                        'edpuser_annotationdriver' => array(
                            'class'           => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                            'namespace'       => 'EdpUser\Model',
                            'paths'           => array(__DIR__ . '/../src/EdpUser/Model'),
                        ),
                    ),
                )
            ),
            'edpuser_user_service' => array(
                'parameters' => array(
                    'userMapper' => 'edpuser_user_mapper'
                ),
            ),
            'edpuser_register_form' => array(
                'parameters' => array(
                    'userMapper' => 'edpuser_user_mapper'
                ),
            ),
            'EdpUser\Mapper\UserDoctrine' => array(
                'parameters' => array(
                    'em' => 'edpuser_doctrine_em'
                ),
            ),
            'EdpUser\Mapper\UserZendDb' => array(
                'parameters' => array(
                    'readAdapter'  => 'edpuser_read_db',
                    'writeAdapter' => 'edpuser_write_db',
                ),
            ),
            'Zend\View\PhpRenderer' => array(
                'parameters' => array(
                    'options'  => array(
                        'script_paths' => array(
                            'edpuser' => __DIR__ . '/../views',
                        ),
                    ),
                ),
            ),
        ),
    ),
);
