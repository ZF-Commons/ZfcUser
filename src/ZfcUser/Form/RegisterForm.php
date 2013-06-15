<?php

namespace ZfcUser\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class RegisterForm extends Form implements InputFilterProviderInterface
{
    public function __construct()
    {
        parent::__construct(null);

        $this->add(array(
            'name' => 'displayName',
            'type' => 'text',
            'options' => array(
                'label' => 'Display Name'
            )
        ));

        $this->add(array(
            'name' => 'email',
            'type' => 'email',
            'options' => array(
                'label' => 'Email'
            )
        ));

        $this->add(array(
            'name' => 'password',
            'type' => 'password',
            'options' => array(
                'label' => 'Password'
            )
        ));

        $this->add(array(
            'name' => 'confirm-password',
            'type' => 'password',
            'options' => array(
                'label' => 'Confirm Password'
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Register'
            )
        ));
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array(
            'email' => array(
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'emailaddress'
                    )
                )
            ),
            'password' => array(
                'required' => true
            ),
            'confirm-password' => array(
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'identical',
                        'options' => array(
                            'token' => 'password'
                        )
                    )
                )
            )
        );
    }
}