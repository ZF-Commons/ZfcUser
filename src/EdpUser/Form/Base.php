<?php

namespace EdpUser\Form;

use Zend\Form\Form,
    EdpUser\Mapper\UserInterface as UserMapper;

class Base extends Form
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
                //    'entity'  => 'EdpUser\Entity\User',
                //    'field'   => 'username'
                //))
            ),
            'required'   => true,
            'label'      => 'Username',
        ));

        $this->addElement('text', 'email', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                'EmailAddress',
                $this->emailValidator,
            ),
            'required'   => true,
            'label'      => 'Email',
        ));

        $this->addElement('text', 'display_name', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', true, array(3, 128))
            ),
            'required'   => true,
            'label'      => 'Display Name',
        ));

        $this->addElement('password', 'password', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', true, array(6, 128))
            ),
            'required'   => true,
            'label'      => 'Password',
        ));

        $this->addElement('password', 'passwordVerify', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
               array('Identical', false, array('token' => 'password'))
            ),
            'required'   => true,
            'label'      => 'Password Verify',
        ));

        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
        ));

        $this->addElement('hidden', 'userId', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
        ));
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
