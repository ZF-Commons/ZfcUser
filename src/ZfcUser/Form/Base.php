<?php

namespace ZfcUser\Form;

use Zend\Form\Form,
    ZfcUser\Mapper\UserInterface as UserMapper,
    EdpCommon\Form\ProvidesEventsForm;

class Base extends ProvidesEventsForm
{
    protected $emailValidator;
    protected $userMapper;

    public function initLate()
    {
        $this->setMethod('post');

        $this->addElement('text', 'username', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', true, array(3, 255)),
                //array('\SpiffyDoctrine\Validator\NoEntityExists', true, array(
                //    'em'      => $this->getEntityManager(),
                //    'entity'  => 'ZfcUser\Entity\User',
                //    'field'   => 'username'
                //))
            ),
            'required'   => true,
            'label'      => 'Username',
            'order'      => 100,
        ));

        $this->addElement('text', 'email', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                'EmailAddress',
                $this->emailValidator,
            ),
            'required'   => true,
            'label'      => 'Email',
            'order'      => 200,
        ));

        $this->addElement('text', 'display_name', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', true, array(3, 128))
            ),
            'required'   => true,
            'label'      => 'Display Name',
            'order'      => 300,
        ));

        $this->addElement('password', 'password', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', true, array(6, 128))
            ),
            'required'   => true,
            'label'      => 'Password',
            'order'      => 400,
        ));

        $this->addElement('password', 'passwordVerify', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
               array('Identical', false, array('token' => 'password'))
            ),
            'required'   => true,
            'label'      => 'Password Verify',
            'order'      => 405,
        ));

        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'order'    => 1000,
        ));

        $this->addElement('hidden', 'userId', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'order'      => -100,
        ));

        $this->addElement('hash', 'csrf', array(
            'ignore'     => true,
            'decorators' => array('ViewHelper'),
            'order'      => -100,
        ));

        $this->events()->trigger('init', $this);
    }

    /**
     * setUserMapper 
     * 
     * @param UserMapper $userMapper 
     * @return User
     */
    public function setUserMapper(UserMapper $userMapper)
    {
        $this->userMapper = $userMapper;
        $this->setEmailValidator($this->userMapper->getEmailValidator());
        $this->initLate();
        return $this;
    }

    public function setEmailValidator($emailValidator)
    {
        $this->emailValidator = $emailValidator;
        return $this;
    }
}
