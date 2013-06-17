<?php

return array(
    'modules' => array(
        'SpiffyTest',
        'ZfcUser' // replace this with your own module list
    ),
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            '../../../config/autoload/{,*.}{global,local}.php',
        ),
        'module_paths' => array(
            'module',
            'vendor',
        ),
    ),
);
