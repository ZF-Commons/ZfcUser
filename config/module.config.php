<?php

return array(
    'controllers'     => include 'controller.config.php',
    'router'          => include 'router.config.php',
    'service_manager' => include 'service.config.php',

    // See ZfcUser\Options\ModuleOptions for a list of all options.
    'zfc_user' => array(),

    'view_manager' => array(
        'template_map' => array(
            'zfc-user/partial/form'      => __DIR__ . '/../view/zfc-user/partial/form.phtml',
            'zfc-user/user/index'        => __DIR__ . '/../view/zfc-user/user/index.phtml',
            'zfc-user/login/login'       => __DIR__ . '/../view/zfc-user/login/login.phtml',
            'zfc-user/register/register' => __DIR__ . '/../view/zfc-user/register/register.phtml',
        )
    )
);