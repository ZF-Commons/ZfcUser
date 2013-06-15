<?php

return array(
    'controllers'     => include 'controller.config.php',
    'router'          => include 'router.config.php',
    'service_manager' => include 'service.config.php',

    // See ZfcUser\Options\ModuleOptions for a list of all options.
    'zfc_user' => array(),

    'view_manager' => array(
        'template_map' => array(
            'zfc-user/user/index'    => __DIR__ . '/../view/zfc-user/user/index.phtml',
            'zfc-user/user/login'    => __DIR__ . '/../view/zfc-user/user/login.phtml',
            'zfc-user/user/register' => __DIR__ . '/../view/zfc-user/user/register.phtml',
        )
    )
);