<?php
return array(
    'edpuser' => array(
        'user_model_class'         => 'EdpUser\Model\User',
        'password_hash_algorithm'  => 'sha512',
        'enable_username'          => false,
        'enable_display_name'      => false,
        'require_activation'       => false,
        'login_after_registration' => false,
    ),
    'di' => array(
        'instance' => array(
            'alias' => array(
                'user'                  => 'EdpUser\Controller\UserController',
                'edpuser_register_form' => 'EdpUser\Form\Register',
                'edpuser_login_form'    => 'EdpUser\Form\Login',
                'edpuser_user_mapper'   => 'EdpUser\Mapper\UserDoctrine',
                'edpuser_user_service'  => 'EdpUser\Service\User',
                'edpuser_write_db'      => 'Zend\Db\Adapter\DiPdoMysql',
                'edpuser_read_db'       => 'edpuser_write_db',
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
                    'em' => 'doctrine_em'
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
                            'user' => __DIR__ . '/../views',
                        ),
                    ),
                ),
            ),
        ),
    ),
);
