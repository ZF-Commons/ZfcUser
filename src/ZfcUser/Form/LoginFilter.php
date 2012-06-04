<?php

namespace ZfcUser\Form;

use Zend\InputFilter\InputFilter,
    ZfcUser\Module as ZfcUser;

class LoginFilter extends InputFilter
{
    public function __construct()
    {
        $identityParams = array(
            'name'          => 'identity',
            'required'      => true,
            'validators'    => array()
        );

        $identityFields = ZfcUser::getOption('auth_identity_fields')->toArray();
        if (count($identityFields) == 1 && array_pop($identityFields) == 'email') {
            $validators = array('name' => 'EmailAddress');
            array_push($validators, $identityParams['validators']); 
        }

        $this->add($identityParams);

        $this->add(array(
            'name'          => 'credential',
            'required'      => true,
            'validators'    => array(
                array(
                    'name'      => 'StringLength',
                    'options'   => array(
                        'min'   => 6,
                        'max'   => 999
                    ),
                ),
            ),
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
        ));
    }
}
