<?php

namespace ZfcUser\Form;

use Zend\Form\Form,
    ZfcUser\Module,
    Zend\Form\Element\Captcha as Captcha;

class Register extends Base
{
    protected $captcha_element= null;

    public function setCaptchaElement(Captcha $captcha_element)
    {
        $this->captcha_element= $captcha_element;
    }
    
    public function initLate()
    {
        parent::initLate();
        $this->removeElement('userId');
        if (!Module::getOption('enable_username')) {
            $this->removeElement('username');
        }
        if (!Module::getOption('enable_display_name')) {
            $this->removeElement('display_name');
        }
        if (Module::getOption('registration_form_captcha')) {
            if($this->captcha_element==null)
            {
                $this->captcha_element= new Captcha('captcha', 
                    array(
                        'label'      => 'Please enter the 5 letters displayed below:',
                        'required'   => true,
                        'captcha'    => array(
                            'captcha' => 'Figlet',
                            'wordlen'=>5,
                            'timeout'=>300,
                        ),
                        'order'      => 500,
                    )
                );
            }
            $this->addElement($this->captcha_element, 'captcha');
        }
        $this->getElement('submit')->setLabel('Register');
    }
}
