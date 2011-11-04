<?php
return array(
    'di' => array(
        'instance' => array(
            'alias' => array(
                'user'                  => 'EdpUser\Controller\UserController',
                'edpuser-register-form' => 'EdpUser\Form\Register',
                'edpuser-login-form'    => 'EdpUser\Form\Login',
                'edpuser-user-service'  => 'EdpUser\Service\User',
            ),
            'doctrine' => array(
                'parameters' => array(
                    'config' => array(
                        'metadata-driver-impl' => array(
                            'edpuser-annotationdriver' => array(
                                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                                'namespace' => 'EdpUser\Entity',
                                'paths'     => array(__DIR__ . '/../src/EdpUser/Entity'),
                                'cache-class' => 'Doctrine\Common\Cache\ArrayCache',
                                'cache-namespace' => 'edpuser_annotation',
                            )
                        )
                    )
                )
            ),
            'edpuser-user-service' => array(
                'parameters' => array(
                    'doctrine' => 'doctrine',
                ),
            ),
            'edpuser-register-form' => array(
                'parameters' => array(
                    'doctrine' => 'doctrine'
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
