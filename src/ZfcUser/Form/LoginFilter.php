<?php

namespace ZfcUser\Form;

use Zend\InputFilter\InputFilter,
    ZfcUser\Module as ZfcUser;

class LoginFilter extends InputFilter
{
    public function __construct()
    {
        if (ZfcUser::getOption('enable_username')) {
            $this->add(array(
                'name'          => 'identity',
                'required'      => true,
            ));
        } else {
            $this->add(array(
                'name'          => 'identity',
                'required'      => true,
                'validators'    => array(
                    array('name' => 'EmailAddress'),
                ),
            ));
        }

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
