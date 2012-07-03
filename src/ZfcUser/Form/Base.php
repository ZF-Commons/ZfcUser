<?php

namespace ZfcUser\Form;

use Zend\Form\Form;
use Zend\Form\Element\Csrf;
use ZfcBase\Form\ProvidesEventsForm;

class Base extends ProvidesEventsForm
{
    public function __construct()
    {
        parent::__construct();

        $this->add(array(
            'name' => 'username',
            'options' => array(
                'label' => 'Username',
            ),
            'attributes' => array(
                'type' => 'text'
            ),
        ));

        $this->add(array(
            'name' => 'email',
            'options' => array(
                'label' => 'Email',
            ),
            'attributes' => array(
                'type' => 'text'
            ),
        ));

        $this->add(array(
            'name' => 'displayName',
            'options' => array(
                'label' => 'Display Name',
            ),
            'attributes' => array(
                'type' => 'text'
            ),
        ));

        $this->add(array(
            'name' => 'password',
            'options' => array(
                'label' => 'Password',
            ),
            'attributes' => array(
                'type' => 'password'
            ),
        ));

        $this->add(array(
            'name' => 'passwordVerify',
            'options' => array(
                'label' => 'Password Verify',
            ),
            'attributes' => array(
                'type' => 'password'
            ),
        ));

        if ($this->getRegistrationOptions()->getUseRegistrationFormCaptcha()) {
            $this->add(array(
                'name' => 'captcha',
                'type' => 'Zend\Form\Element\Captcha',
                'options' => array(
                    'label' => 'Please type the following text',
                ),
                'attributes' => array(
                    'captcha' => $this->getOptions()->getFormCaptchaOptions(),
                ),
            ));
        }

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'value' => 'Submit',
                'type' => 'submit'
            ),
        ));

        $this->add(array(
            'name' => 'userId',
            'attributes' => array(
                'type' => 'hidden'
            ),
        ));

        // @TODO: Fix this... getValidator() is a protected method.
        //$csrf = new Csrf('csrf');
        //$csrf->getValidator()->setTimeout($this->getRegistrationOptions()->getUserFormTimeout());
        //$this->add($csrf);

        $this->getEventManager()->trigger('init', $this);
    }
}
