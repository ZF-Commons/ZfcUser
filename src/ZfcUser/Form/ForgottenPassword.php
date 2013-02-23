<?php

namespace ZfcUser\Form;

use Zend\Form\Element;
use ZfcBase\Form\ProvidesEventsForm;

/**
 * Form for password retrieval.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class ForgottenPassword extends ProvidesEventsForm
{
    public function __construct()
    {
        parent::__construct();

        $this->add(array(
            'name' => 'email',
            'options' => array(
                'label' => 'Email',
            ),
            'attributes' => array(
                'type' => 'text'
            ),
        ));

        $submitElement = new Element\Button('submit');
        $submitElement
            ->setLabel('Submit')
            ->setAttributes(array(
                'type'  => 'submit',
            ));

        $this->add($submitElement, array(
            'priority' => -100,
        ));

        // @todo Add CRSF & Captcha
    }

    public function getEmail()
    {
        return $this->get('email')->getValue();
    }
}
