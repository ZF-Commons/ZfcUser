<?php

return array(
    'invokables' => array(
        'ZfcUser\Form\LoginForm' => 'ZfcUser\Form\LoginForm',
    ),

    'factories' => array(
        'ZfcUser\Form\RegisterForm'       => 'ZfcUser\Form\RegisterFormFactory',
        'ZfcUser\Form\RegisterHydrator'   => 'ZfcUser\Form\RegisterHydratorFactory',
        'ZfcUser\Form\PasswordStrategy'   => 'ZfcUser\Form\PasswordStrategyFactory',

        'ZfcUser\Options\ModuleOptions'   => 'ZfcUser\Options\ModuleOptionsFactory',

        'ZfcUser\Service\LoginService'    => 'ZfcUser\Service\LoginServiceFactory',
        'ZfcUser\Service\RegisterService' => 'ZfcUser\Service\RegisterServiceFactory',
    )
);