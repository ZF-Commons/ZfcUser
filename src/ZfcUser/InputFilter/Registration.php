<?php
namespace ZfcUser\InputFilter;

use Zend\InputFilter\InputFilter;
use ZfcUser\Options\RegistrationOptionsInterface;

/**
 * Class Registration
 * @package ZfcUser\InputFilter
 */
class Registration extends InputFilter
{
    /**
     * @var RegistrationOptionsInterface
     */
    protected $options;

    /**
     * @param RegistrationOptionsInterface $options
     */
    public function __construct(RegistrationOptionsInterface $options)
    {
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if ($this->options->getEnableUsername()) {
            $this->add([
                'name' => 'username',
                'required' => true,
                'filters' => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        // TODO: Make min/max configurable
                        'options' => [
                            'min' => 3,
                            'max' => 255,
                        ],
                    ],
                ],
            ]);
        }

        $this->add([
            'name' => 'email',
            'required' => true,
            'filters' => [
                [
                    'name' => 'StringTrim',
                ],
            ],
            'validators' => [
                // TODO: Add unique email validator
                [
                    'name' => 'EmailAddress',
                ],
            ],
        ]);

        if ($this->options->getEnableDisplayName()) {
            $this->add([
                'name' => 'display_name',
                'required' => true,
                'filters' => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        // TODO: Make min/max configurable
                        'options' => [
                            'min' => 3,
                            'max' => 128,
                        ],
                    ],
                ],
            ]);
        }

        $this->add([
            'name' => 'password',
            'require' => true,
            'filters' => [
                [
                    'name' => 'StringTrim',
                ],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    // TODO: Make min configurable
                    'options' => [
                        'min' => 6,
                    ],
                ],
            ],
        ]);

        $this->add([
            'name' => 'passwordVerify',
            'required' => true,
            'filters' => [
                [
                    'name' => 'StringTrim',
                ],
            ],
            'validators' => [
                [
                    'name' => 'Identical',
                    'options' => [
                        'token' => 'password',
                    ],
                ],
            ],
        ]);
    }
}
