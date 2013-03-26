<?php

namespace ZfcUser\Form;

use Zend\InputFilter\InputFilter;
use ZfcUser\Options\AuthenticationOptionsInterface;

class ForgotPasswordFilter extends InputFilter
{
    public function __construct(AuthenticationOptionsInterface $options)
    {
        $identityParams = array(
            'name'       => 'identity',
            'required'   => true,
            'validators' => array()
        );

        $identityFields = $options->getAuthIdentityFields();
        if ($identityFields == array('email')) {
            $validators = array('name' => 'EmailAddress');
            array_push($validators, $identityParams['validators']);
        }

        $this->add($identityParams);
    }
}
