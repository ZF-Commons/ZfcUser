<?php
namespace ZfcUser\InputFilter;

use Zend\InputFilter\InputFilter;
use ZfcUser\Options\AuthenticationOptionsInterface;

/**
 * Class ChangePasswordFilter
 * @package ZfcUser\InputFilter
 */
class ChangePasswordFilter extends InputFilter
{
    /**
     * @var AuthenticationOptionsInterface
     */
    protected $authenticationOptions;

    /**
     * @param AuthenticationOptionsInterface $authenticationOptions
     */
    public function __construct(AuthenticationOptionsInterface $authenticationOptions)
    {
        $this->authenticationOptions = $authenticationOptions;
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->add([
            // TODO: Check if password is correct
            'name'       => 'credential',
            'required'   => true,
            'filters' => [
                [
                    'name' => 'StringTrim'
                ],
            ],
        ]);

        $this->add([
            'name'       => 'newCredential',
            'required'   => true,
            'filters' => [
                [
                    'name' => 'StringTrim',
                ],
            ],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    // TODO: Make min configurable
                    'options' => [
                        'min' => 6,
                    ],
                ],
            ],
        ]);

        $this->add([
            'name'       => 'newCredentialVerify',
            'required'   => true,
            'filters' => [
                [
                    'name' => 'StringTrim',
                ],
            ],
            'validators' => [
                [
                    'name' => 'identical',
                    'options' => [
                        'token' => 'newCredential',
                    ],
                ],
            ],
        ]);
    }
}
