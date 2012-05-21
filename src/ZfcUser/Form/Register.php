<?php

namespace ZfcUser\Form;

use Zend\Form\Form,
    ZfcUser\Module,
    Zend\Form\Element\Captcha as Captcha;

class Register extends Base
{
    protected $captcha_element= null;

    public function __construct()
    {
        parent::__construct();
        
        $this->remove('userId');
        if (!Module::getOption('enable_username')) {
            $this->remove('username');
        }
        if (!Module::getOption('enable_display_name')) {
            $this->remove('display_name');
        }
        if (Module::getOption('registration_form_captcha') && $this->captcha_element) {
            $this->add($this->captcha_element, array('name'=>'captcha'));
        }
        $this->get('submit')->setAttribute('Label', 'Register');
    }

    public function setCaptchaElement(Captcha $captcha_element)
    {
        $this->captcha_element= $captcha_element;
    }
    
    public function initLate()
    {
        parent::initLate();
    }
}
