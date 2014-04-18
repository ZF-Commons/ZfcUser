<?php
namespace ZfcUser\InputFilter;

use Zend\InputFilter\InputFilter;
use ZfcUser\Options\AuthenticationOptionsInterface;

/**
 * Class ChangeEmailFilter
 * @package ZfcUser\InputFilter
 */
class ChangeEmailFilter extends InputFilter
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
            'name'       => 'newIdentity',
            'required'   => true,
            'validators' => [
                [
                    'name' => 'EmailAddress'
                ],
                [
                    'name' => 'ZfcUser\Validator\NoRecordExistsValidator',
                    'options' => [
                        'key' => 'email',
                    ],
                ],
            ],
        ]);

        $this->add([
            'name'       => 'newIdentityVerify',
            'required'   => true,
            'validators' => [
                [
                    'name' => 'identical',
                    'options' => [
                        'token' => 'newIdentity',
                    ],
                ],
            ],
        ]);

        $this->add([
            'name'       => 'credential',
            'required'   => true,
            'filters' => [
                [
                    'name' => 'StringTrim'
                ],
            ],
            'validators' => [
                [
                    'name' => 'ZfcUser\Validator\VerifyPasswordValidator',
                ],
            ],
        ]);
    }
}