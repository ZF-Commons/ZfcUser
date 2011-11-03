<?php

namespace EdpUser\Form;

use Zend\Form\Form,
    Edp\Common\DbMapper;

class Base extends Form
{
    protected $entityManager;

    public function initLate()
    {
        $this->setMethod('post');

        $this->addElement('text', 'username', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', true, array(3, 255)),
                //array('\SpiffyDoctrine\Validator\NoEntityExists', true, array(
                //    'em'      => $this->entityManager,
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
                //array('\SpiffyDoctrine\Validator\NoEntityExists', true, array(
                //    'em'      => $this->entityManager,
                //    'entity'  => 'EdpUser\Entity\User',
                //    'field'   => 'email'
                //))
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
 
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
        $this->initLate();
        return $this;
    }
}

