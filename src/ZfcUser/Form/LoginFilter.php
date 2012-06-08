<?php

namespace ZfcUser\Form;

use Zend\InputFilter\InputFilter;
use ZfcUser\Module as ZfcUser;

class LoginFilter extends InputFilter
{
    public function __construct()
    {
        $identityParams = array(
            'name'       => 'identity',
            'required'   => true,
            'validators' => array()
        );

        $identityFields = ZfcUser::getOption('auth_identity_fields');
        if ($identityFields == array('email')) {
            $validators = array('name' => 'EmailAddress');
            array_push($validators, $identityParams['validators']); 
        }

        $this->add($identityParams);

        $this->add(array(
            'name'       => 'credential',
            'required'   => true,
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'min' => 6,
                        'max' => 999
                    ),
                ),
            ),
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
        ));
    }
}
