<?php

namespace ZfcUser\Form;

use Zend\InputFilter\InputFilter,
    ZfcUser\Module as ZfcUser;

class RegisterFilter extends InputFilter
{
    protected $emailValidator;
    protected $usernameValidator;

    public function __construct($emailValidator, $usernameValidator)
    {
        $this->emailValidator = $emailValidator;
        $this->usernameValidator = $usernameValidator;

        if (ZfcUser::getOption('enable_username')) {
            $this->add(array(
                'name'          => 'username',
                'required'      => true,
                'validators'    => array(
                    array(
                        'name'      => 'StringLength',
                        'options'   => array(
                            'min'   => 3,
                            'max'   => 255,
                        ),
                    ),
                    $this->usernameValidator,
                ),
            ));
        }

        $this->add(array(
            'name'          => 'email',
            'required'      => true,
            'validators'    => array(
                array(
                    'name'      => 'EmailAddress'
                ),
                $this->emailValidator
            ),
        ));

        if (ZfcUser::getOption('enable_display_name')) {
            $this->add(array(
                'name'          => 'display_name',
                'required'      => true,
                'filters'       => array(array('name' => 'StringTrim')),
                'validators'    => array(
                    array(
                        'name'      => 'StringLength',
                        'options'   => array(
                            'min'   => 3,
                            'max'   => 128,
                        ),
                    ),
                ),
            ));
        }

        $this->add(array(
            'name'          => 'password',
            'required'      => true,
            'filters'       => array(array('name' => 'StringTrim')),
            'validators'    => array(
                array(
                    'name'      => 'StringLength',
                    'options'   => array(
                        'min'   => 6,
                        'max'   => 128,
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name'          => 'passwordVerify',
            'required'      => true,
            'filters'       => array(array('name' => 'StringTrim')),
            'validators'    => array(
                array(
                    'name'      => 'StringLength',
                    'options'   => array(
                        'min'   => 6,
                        'max'   => 128,
                    ),
                ),
                array(
                    'name'      => 'Identical',
                    'options'   => array(
                        'token' => 'password',
                    ),
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
 
    public function getUsernameValidator()
    {
        return $this->usernameValidator;
    }
 
    public function setUsernameValidator($usernameValidator)
    {
        $this->usernameValidator = $usernameValidator;
        return $this;
    }
}
