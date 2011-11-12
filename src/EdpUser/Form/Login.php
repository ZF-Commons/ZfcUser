<?php

namespace EdpUser\Form;

use Zend\Form\Form,
    EdpCommon\Form\ProvidesEventsForm,
    EdpUser\Module;

class Login extends ProvidesEventsForm
{
    public function init()
    {
        $this->setMethod('post');

        $this->addDecorator('FormErrors')
             ->addDecorator('FormElements')
             ->addDecorator('HtmlTag', array('tag' => 'dl', 'class' => 'login_form'))
             ->addDecorator('FormDecorator');

        $this->addElement('text', 'email', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                'EmailAddress',
            ),
            'required'   => true,
            'label'      => 'Email',
        ));
        

        if (Module::getOption('enable_username')) {
            $emailElement = $this->getElement('email');
            $emailElement->removeValidator('EmailAddress')
                         ->setLabel('Email or Username'); // @TODO: make translation-friendly
        }

        
        $this->addElement('password', 'password', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', true, array(6, 999))
            ),
            'required'   => true,
            'label'      => 'Password',
        ));

        $this->addElement('submit', 'login', array(
            'ignore'   => true,
            'label'    => 'Sign In',
        ));

        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
            'decorators' => array('ViewHelper'),
        ));

        $this->events()->trigger('init', $this);

    }
}
