<?php

namespace ZfcUser\Form;

use Zend\Form\Form,
    ZfcUser\Module;

class Register extends Base
{
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
            $this->addElement('captcha', 'captcha', array(
                'label'      => 'Please enter the 5 letters displayed below:',
                'required'   => true,
                'captcha'    => array(
                    'captcha' => 'Figlet',
                    'wordLen' => 5,
                    'timeout' => 300
                ),
                'order'      => 500,
            ));
        }
        $this->getElement('submit')->setLabel('Register');
    }
}
