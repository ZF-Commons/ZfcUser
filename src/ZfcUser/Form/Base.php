<?php

namespace ZfcUser\Form;

use Zend\Form\Form,
    ZfcUser\Mapper\UserInterface as UserMapper,
    ZfcBase\Form\ProvidesEventsForm;

class Base extends ProvidesEventsForm
{
    protected $emailValidator;
    protected $usernameValidator;

    public function initLate()
    {
        $this->add(array(
            'name' => 'username',
            'label' => 'Username',
            'attributes' => array(
                'type' => 'text'
            ),
        ));

        $this->add(array(
            'name' => 'email',
            'label' => 'Email',
            'attributes' => array(
                'type' => 'text'
            ),
        ));

        $this->add(array(
            'name' => 'display_name',
            'label' => 'Display Name',
            'attributes' => array(
                'type' => 'text'
            ),
        ));

        $this->add(array(
            'name' => 'password',
            'label' => 'Password',
            'attributes' => array(
                'type' => 'password'
            ),
        ));

        $this->add(array(
            'name' => 'passwordVerify',
            'label' => 'Password Verify',
            'attributes' => array(
                'type' => 'password'
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'label' => 'Submit',
            'attributes' => array(
                'type' => 'submit'
            ),
        ));

        $this->add(array(
            'name' => 'userId',
            'attributes' => array(
                'type' => 'hidden'
            ),
        ));

        $this->add(array(
            'name' => 'csrf',
            'attributes' => array(
                'type' => 'hash'
            ),
        ));

//        $this->addElement('text', 'username', array(
//            'filters'    => array('StringTrim'),
//            'validators' => array(
//                array('StringLength', true, array(3, 255)),
//                $this->usernameValidator,
//            ),
//            'required'   => true,
//            'label'      => 'Username',
//            'order'      => 100,
//        ));
//
//        $this->addElement('text', 'email', array(
//            'filters'    => array('StringTrim'),
//            'validators' => array(
//                'EmailAddress',
//                $this->emailValidator,
//            ),
//            'required'   => true,
//            'label'      => 'Email',
//            'order'      => 200,
//        ));
//
//        $this->addElement('text', 'display_name', array(
//            'filters'    => array('StringTrim'),
//            'validators' => array(
//                array('StringLength', true, array(3, 128))
//            ),
//            'required'   => true,
//            'label'      => 'Display Name',
//            'order'      => 300,
//        ));
//
//        $this->addElement('password', 'password', array(
//            'filters'    => array('StringTrim'),
//            'validators' => array(
//                array('StringLength', true, array(6, 128))
//            ),
//            'required'   => true,
//            'label'      => 'Password',
//            'order'      => 400,
//        ));
//
//        $this->addElement('password', 'passwordVerify', array(
//            'filters'    => array('StringTrim'),
//            'validators' => array(
//               array('Identical', false, array('token' => 'password'))
//            ),
//            'required'   => true,
//            'label'      => 'Password Verify',
//            'order'      => 405,
//        ));
//
//        $this->addElement('submit', 'submit', array(
//            'ignore'   => true,
//            'order'    => 1000,
//        ));
//
//        $this->addElement('hidden', 'userId', array(
//            'filters'    => array('StringTrim'),
//            'required'   => true,
//            'order'      => -100,
//        ));
//
//        $this->addElement('hash', 'csrf', array(
//            'ignore'     => true,
//            'decorators' => array('ViewHelper'),
//            'order'      => -100,
//        ));

        $this->events()->trigger('init', $this);
    }

    public function setEmailValidator($emailValidator)
    {
        $this->emailValidator = $emailValidator;
        return $this;
    }

    public function setUsernameValidator($usernameValidator)
    {
        $this->usernameValidator = $usernameValidator;
        $this->initLate(); // yuck
        return $this;
    }
}
