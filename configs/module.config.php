<?php
return array(
    'di' => array(
        'instance' => array(
            'doctrine-container' => array(
                'parameters' => array(
                    'em' => array(
                        'default' => array(
                            'driver' => array(
                                'paths' => array(
                                    'EdpUser' => __DIR__ . '/../src/EdpUser/Entity',
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
