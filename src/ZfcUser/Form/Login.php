<?php

namespace ZfcUser\Form;

use Zend\Form\Form,
    Zend\Form\Element\Csrf,
    ZfcBase\Form\ProvidesEventsForm,
    ZfcUser\Module as ZfcUser;

class Login extends ProvidesEventsForm
{
    public function __construct($name = null)
    {
        parent::__construct($name);

        $this->add(array(
            'name' => 'identity',
            'attributes' => array(
                'label' => 'Email',
                'type' => 'text'
            ),
        ));

        if (ZfcUser::getOption('enable_username')) {
            $emailElement = $this->getElement('identity');
            $emailElement->setLabel('Email or Username'); // @TODO: make translation-friendly
        }
        
        $this->add(array(
            'name' => 'credential',
            'attributes' => array(
                'label' => 'Password',
                'type' => 'password',
            ),
        ));

        $this->add(new Csrf('csrf'));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'label' => 'Submit',
                'type' => 'submit'
            ),
        ));

        $this->events()->trigger('init', $this);
    }
}
