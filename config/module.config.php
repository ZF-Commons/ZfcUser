<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            'zfcuser' => __DIR__ . '/../view',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'zfcuser' => 'ZfcUser\Controller\UserController',
        ),
    ),

    'form_elements' => [
        'factories' => [
            'ZFcUser\Form\Element\IdentityElement'  => 'ZfcUser\Factory\Form\Element\IdentityElementFactory',

            'ZfcUser\Form\ChangeEmailForm'          => 'ZfcUser\Factory\Form\ChangeEmailFormFactory',
            'ZfcUser\Form\ChangePasswordForm'       => 'ZfcUser\Factory\Form\ChangePasswordFormFactory',
            'ZfcUser\Form\LoginForm'                => 'ZfcUser\Factory\Form\LoginFormFactory',
            'ZfcUser\Form\RegistrationForm'         => 'ZfcUser\Factory\Form\RegistrationFormFactory',
        ],
        'shared' => [
            'ZfcUser\Form\ChangeEmailForm'      => true,
            'ZfcUser\Form\ChangePasswordForm'   => true,
            'ZfcUser\Form\LoginForm'            => true,
            'ZfcUser\Form\RegistrationForm'     => true,
        ],
    ],

    'hydrators' => [
        'invokables' => [
            'ZfcUser\Hydrator\RegistrationHydrator' => 'Zend\Stdlib\Hydrator\ClassMethods',
        ],
    ],

    'input_filters' => [
        'factories' => [
            'ZfcUser\InputFilter\ChangeEmailFilter'     => 'ZfcUser\Factory\InputFilter\ChangeEmailFilterFactory',
            'ZfcUser\InputFilter\ChangePasswordFilter'  => 'ZfcUser\Factory\InputFilter\ChangePasswordFilterFactory',
            'ZfcUser\InputFilter\RegistrationFilter'    => 'ZfcUser\Factory\InputFilter\RegistrationFilterFactory',
        ],
        'invokables' => [
            'ZfcUser\InputFilter\LoginFilter' => 'ZfcUser\InputFilter\LoginFilter',
        ],
    ],

    'validators' => [
        'factories' => [
            'ZfcUser\Validator\NoRecordExistsValidator' => 'ZfcUser\Factory\Validator\NoRecordExistsValidatorFactory',
            'ZfcUser\Validator\RecordExistsValidator'   => 'ZfcUser\Factory\Validator\RecordExistsValidatorFactory',
            'ZfcUser\Validator\VerifyPasswordValidator' => 'ZfcUser\Factory\Validator\VerifyPasswordValidatorFactory',
        ],
    ],

    'view_helpers' => [
        'aliases' => [
            'zfcUserIdentity'   => 'ZfcUser\View\Helper\IdentityHelper',
            'zfcUserLoginForm'  => 'ZfcUser\View\Helper\LoginFormHelper',
        ],
        'factories' => [
            'ZfcUser\View\Helper\IdentityHelper'    => 'ZfcUser\Factory\View\Helper\IdentityHelperFactory',
            'ZfcUser\View\Helper\LoginFormHelper'   => 'ZfcUser\Factory\View\Helper\LoginFormHelperFactory',
        ],
    ],

    'service_manager' => array(
        'aliases' => array(
            'zfcuser_zend_db_adapter' => 'Zend\Db\Adapter\Adapter',
        ),
    ),
    'router' => array(
        'routes' => array(
            'zfcuser' => array(
                'type' => 'Literal',
                'priority' => 1000,
                'options' => array(
                    'route' => '/user',
                    'defaults' => array(
                        'controller' => 'zfcuser',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'login' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/login',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'login',
                            ),
                        ),
                    ),
                    'authenticate' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/authenticate',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'authenticate',
                            ),
                        ),
                    ),
                    'logout' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/logout',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'logout',
                            ),
                        ),
                    ),
                    'register' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/register',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'register',
                            ),
                        ),
                    ),
                    'changepassword' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/change-password',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'changepassword',
                            ),
                        ),
                    ),
                    'changeemail' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/change-email',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action' => 'changeemail',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
