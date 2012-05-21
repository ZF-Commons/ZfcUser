<?php

namespace ZfcUser\Form;

use Zend\Form\Form,
    Zend\Form\Element\Csrf,
    ZfcUser\Mapper\UserInterface as UserMapper,
    ZfcBase\Form\ProvidesEventsForm;

class Base extends ProvidesEventsForm
{
    public function __construct()
    {
        parent::__construct();

        $this->add(array(
            'name' => 'username',
            'attributes' => array(
                'label' => 'Username',
                'type' => 'text'
            ),
        ));

        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'label' => 'Email',
                'type' => 'text'
            ),
        ));

        $this->add(array(
            'name' => 'display_name',
            'attributes' => array(
                'label' => 'Display Name',
                'type' => 'text'
            ),
        ));

        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'label' => 'Password',
                'type' => 'password'
            ),
        ));

        $this->add(array(
            'name' => 'passwordVerify',
            'attributes' => array(
                'label' => 'Password Verify',
                'type' => 'password'
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'label' => 'Submit',
                'type' => 'submit'
            ),
        ));

        $this->add(array(
            'name' => 'userId',
            'attributes' => array(
                'type' => 'hidden'
            ),
        ));

        $this->add(new Csrf('csrf'));

        $this->events()->trigger('init', $this);
    }
}
