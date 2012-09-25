<?php

namespace ZfcUser\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use ZfcBase\Form\ProvidesEventsForm;

class Logout extends ProvidesEventsForm
{
    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->setAttribute('method', 'post');

        $logoutButton = new Element\Button('logout');
        $logoutButton
            ->setLabel('Sign Out')
            ->setAttributes(array(
                'type'  => 'submit',
            ));
        $this->add($logoutButton);

        $csrf = new Element\Csrf('csrf');
        $csrf->setCsrfValidatorOptions(array('timeout' => null));
        $this->add($csrf);

        $this->getEventManager()->trigger('init', $this);
    }
}
