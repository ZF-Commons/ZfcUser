<?php
return array(
    'di' => array(
        'instance' => array(
            'alias' => array(
                'user'                  => 'EdpUser\Controller\UserController',
                'edpuser-register-form' => 'EdpUser\Form\Register',
                'edpuser-login-form'    => 'EdpUser\Form\Login',
                'edpuser-user-mapper'   => 'EdpUser\Mapper\UserDoctrine',
                'edpuser-user-service'  => 'EdpUser\Service\User',
                'edpuser-write-db'      => 'Zend\Db\Adapter\DiPdoMysql',
                'edpuser-read-db'       => 'edpuser-write-db',
            ),
            'edpuser-write-db' => array(
                'parameters' => array(
                    'pdo'    => 'edpuser-pdo',
                    'config' => array(),
                ),
            ),
            'doctrine' => array(
                'parameters' => array(
                    'config' => array(
                        'metadata_driver_impl' => array(
                            'edpuserbase_annotationdriver' => array(
                                'class'           => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                                'namespace'       => 'EdpUser\ModelBase',
                                'paths'           => array(__DIR__ . '/../src/EdpUser/ModelBase'),
                                'cache_class'     => 'Doctrine\Common\Cache\ArrayCache',
                            ),
                            'edpuser_annotationdriver' => array(
                                'class'           => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                                'namespace'       => 'EdpUser\Model',
                                'paths'           => array(__DIR__ . '/../src/EdpUser/Model'),
                                'cache_class'     => 'Doctrine\Common\Cache\ArrayCache',
                            ),
                        )
                    )
                )
            ),
            'edpuser-user-service' => array(
                'parameters' => array(
                    'userMapper' => 'edpuser-user-mapper'
                ),
            ),
            'edpuser-register-form' => array(
                'parameters' => array(
                    'userMapper' => 'edpuser-user-mapper'
                ),
            ),
            'EdpUser\Mapper\UserDoctrine' => array(
                'parameters' => array(
                    'doctrine' => 'doctrine'
                ),
            ),
            'EdpUser\Mapper\UserZendDb' => array(
                'parameters' => array(
                    'doctrine'     => 'doctrine',
                    'readAdapter'  => 'edpuser-read-db',
                    'writeAdapter' => 'edpuser-write-db',
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
