<?php

namespace EdpUser\Form;

use Zend\Form\Form,
    EdpUser\Module;

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
        $this->getElement('submit')->setLabel('Register');
    }
}
