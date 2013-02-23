<?php

namespace ZfcUser\Form;

use Zend\InputFilter\InputFilter;
use ZfcUser\Options\AuthenticationOptionsInterface;

class ResetPasswordFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name'       => 'newCredential',
            'required'   => true,
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'min' => 6,
                    ),
                ),
            ),
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
        ));

        $this->add(array(
            'name'       => 'newCredentialVerify',
            'required'   => true,
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'min' => 6,
                    ),
                ),
                array(
                    'name' => 'identical',
                    'options' => array(
                        'token' => 'newCredential'
                    )
                ),
            ),
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
        ));
    }
}
