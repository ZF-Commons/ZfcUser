<?php

namespace ZfcUser\Form;

use Zend\InputFilter\InputFilter;

class ForgottenPasswordFilter extends InputFilter
{
    protected $emailValidator;

    public function __construct($emailValidator)
    {
        $this->emailValidator = $emailValidator;

        $this->add(array(
            'name'       => 'email',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'EmailAddress'
                ),
                $this->emailValidator
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
