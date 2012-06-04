<?php

namespace ZfcUser\Form;

use Zend\Form\Form,
    Zend\Form\Element\Csrf,
    ZfcBase\Form\ProvidesEventsForm,
    ZfcUser\Module as ZfcUser;

class Login extends ProvidesEventsForm
{
    public function __construct($name = null)
    {
        parent::__construct($name);

        $this->add(array(
            'name' => 'identity',
            'attributes' => array(
                'label' => '',
                'type' => 'text'
            ),
        ));

        $emailElement = $this->get('identity');
        $label = $emailElement->getAttribute('label');
        // @TODO: make translation-friendly
        foreach (ZfcUser::getOption('auth_identity_fields') as $mode) {
            $label = (!empty($label) ? $label . ' or ' : '') . ucfirst($mode);
        }
        $emailElement->setAttribute('label', $label);
        
        $this->add(array(
            'name' => 'credential',
            'attributes' => array(
                'label' => 'Password',
                'type' => 'password',
            ),
        ));

        $this->add(new Csrf('csrf'));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'label' => 'Submit',
                'type' => 'submit'
            ),
        ));

        $this->events()->trigger('init', $this);
    }
}
