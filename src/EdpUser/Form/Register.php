<?php

namespace EdpUser\Form;

use Zend\Form\Form;

class Register extends Base
{
    public function initLate()
    {
        parent::initLate();
        $this->removeElement('userId');
        $this->getElement('submit')->setLabel('Register');
    }
}
