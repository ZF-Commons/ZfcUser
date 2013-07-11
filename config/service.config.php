<?php

return array(
    'invokables' => array(
        'ZfcUser\Form\LoginForm'    => 'ZfcUser\Form\LoginForm',
        'ZfcUser\Form\RegisterForm' => 'ZfcUser\Form\RegisterForm',
    ),

    'factories' => array(
        'ZfcUser\Extension\Authentication' => 'ZfcUser\Extension\AuthenticationFactory',
        'ZfcUser\Extension\Manager'        => 'ZfcUser\Extension\ManagerFactory',
        'ZfcUser\ModuleOptions'            => 'ZfcUser\ModuleOptionsFactory',
    )
);