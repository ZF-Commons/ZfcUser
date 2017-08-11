<?php

namespace ZfcUser\Form;

use Zend\Form\Element;

class Base extends ProvidesEventsForm
{
    public function __construct($name = null)
    {
        parent::__construct($name);

        $this->add(array(
            'name' => 'username',
            'options' => array(
                'label' => 'Username',
            ),
            'attributes' => array(
                'id' => 'username',
                'type' => 'text',
            ),
        ));

        $this->add(array(
            'name' => 'email',
            'options' => array(
                'label' => 'Email',
            ),
            'attributes' => array(
                'id' => 'email',
                'type' => 'text',
            ),
        ));

        $this->add(array(
            'name' => 'display_name',
            'options' => array(
                'label' => 'Display Name',
            ),
            'attributes' => array(
                'id' => 'display_name',
                'type' => 'text',
            ),
        ));

        $this->add(array(
            'name' => 'password',
            'type' => 'password',
            'options' => array(
                'label' => 'Password',
            ),
            'attributes' => array(
                'id' => 'password',
                'type' => 'password',
            ),
        ));

        $this->add(array(
            'name' => 'passwordVerify',
            'type' => 'password',
            'options' => array(
                'label' => 'Password Verify',
            ),
            'attributes' => array(
                'id' => 'passwordVerify',
                'type' => 'password',
            ),
        ));

        $submitElement = new Element\Button('submit');
        $submitElement
            ->setLabel('Submit')
            ->setAttributes(array(
                'type'  => 'submit',
            ));

        $this->add($submitElement, array(
            'priority' => -100,
        ));

        $this->add(array(
            'name' => 'userId',
            'type' => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'type' => 'hidden'
            ),
        ));

        // @TODO: Fix this... getValidator() is a protected method.
        //$csrf = new Element\Csrf('csrf');
        //$csrf->getValidator()->setTimeout($this->getRegistrationOptions()->getUserFormTimeout());
        //$this->add($csrf);

        $this->getEventManager()->trigger('init', $this);
    }

    public function init()
    {
    }
}
