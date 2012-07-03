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
            'name' => 'displayName',
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

        if ($this->getOptions()->getUseRegistrationFormCaptcha()) {
            $this->add(array(
                'name' => 'captcha',
                'type' => 'Zend\Form\Element\Captcha',
                'attributes' => array(
                    'label' => 'Please type the following text',
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
        //$csrf->getValidator()->setTimeout($this->getOptions()->getUserFormTimeout());
        //$this->add($csrf);

        $this->getEventManager()->trigger('init', $this);
    }
}
