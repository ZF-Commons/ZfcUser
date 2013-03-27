<?php

namespace ZfcUser\Form;

use Zend\InputFilter\InputFilter;
use ZfcUser\Options\AuthenticationOptionsInterface;

class ChangeEmailFilter extends InputFilter
{
    protected $emailValidator;

    public function __construct(AuthenticationOptionsInterface $options, $emailValidator)
    {
        $this->emailValidator = $emailValidator;

        $this->add(array(
            'name'       => 'newIdentity',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'EmailAddress'
                ),
                $this->emailValidator
            ),
        ));

        $this->add(array(
            'name'       => 'newIdentityVerify',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'identical',
                    'options' => array(
                        'token' => 'newIdentity'
                    )
                ),
            ),
        ));
    }

    public function getEmailValidator()
    {
        return $this->emailValidator;
    }

    public function setEmailValidator($emailValidator)
    {
        $this->emailValidator = $emailValidator;
        return $this;
    }
}
