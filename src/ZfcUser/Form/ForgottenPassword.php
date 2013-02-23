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
    public function __construct($name = null)
    {
        parent::__construct($name);

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
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Button',
            'attributes' => array(
                'value' => 'Submit',
            ),
        ));

        // @todo Add CRSF & Captcha

        $this->getEventManager()->trigger('init', $this);
    }

    public function getEmail()
    {
        return $this->get('email')->getValue();
    }
}
