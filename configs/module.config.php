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
            'doctrine-annotationdriver' => array(
                'parameters' => array(
                    'paths' => array(
                        'EdpUser' => __DIR__ . '/../src/EdpUser/Entity',
                    ),
                ),
            ),
            'doctrine-driverchain' => array(
                'injections' => array(
                    'addDriver' => array(
                        (int) base_convert('EdpUser', 36, 9) => array('nestedDriver' => 'doctrine-annotationdriver', 'namespace' => 'EdpUser\Entity'),
                    )
                ),
            ),
            'edpuser-user-service' => array(
                'parameters' => array(
                    'entityManager' => 'doctrine-em',
                ),
            ),
            'edpuser-register-form' => array(
                'parameters' => array(
                    'entityManager' => 'doctrine-em'
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
