<?php

return array(
    'invokables' => array(
        'ZfcUser\Form\LoginForm'                 => 'ZfcUser\Form\LoginForm',

        'Zend\Authentication\AuthenticationService' => 'Zend\Authentication\AuthenticationService',
    ),

    'factories' => array(
        'ZfcUser\Form\RegisterForm'       => 'ZfcUser\Form\Factory\RegisterFormFactory',
        'ZfcUser\Form\RegisterHydrator'   => 'ZfcUser\Form\Factory\RegisterHydratorFactory',
        'ZfcUser\Form\PasswordStrategy'   => 'ZfcUser\Form\Factory\PasswordStrategyFactory',

        'ZfcUser\Options\ModuleOptions'   => 'ZfcUser\Options\Factory\ModuleOptionsFactory',

        'ZfcUser\Service\LoginService'    => 'ZfcUser\Service\Factory\LoginServiceFactory',
        'ZfcUser\Service\RegisterService' => 'ZfcUser\Service\Factory\RegisterServiceFactory',
    )
);