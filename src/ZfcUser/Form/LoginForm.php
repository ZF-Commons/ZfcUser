<?php

namespace ZfcUser\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class LoginForm extends Form implements InputFilterProviderInterface
{
    /**
     * @override
     */
    public function __construct()
    {
        parent::__construct(null);

        $this->add(array(
            'name' => 'email',
            'type' => 'text',
            'options' => array(
                'label' => 'Email'
            )
        ), array('priority' => 999));

        $this->add(array(
            'name' => 'password',
            'type' => 'password',
            'options' => array(
                'label' => 'Password'
            )
        ), array('priority' => 888));

        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Login'
            )
        ), array('priority' => -999));
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
            ),
            'password' => array(
                'required' => true
            )
        );
    }
}