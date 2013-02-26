<?php

namespace ZfcUser\Form;

use Zend\Form\Form;
use ZfcBase\Form\ProvidesEventsForm;

class ResetPassword extends ProvidesEventsForm
{
    public function __construct($name = null)
    {
        parent::__construct($name);

        $this->add(array(
            'name' => 'newCredential',
            'options' => array(
                'label' => 'New Password',
            ),
            'attributes' => array(
                'type' => 'password',
            ),
        ));

        $this->add(array(
            'name' => 'newCredentialVerify',
            'options' => array(
                'label' => 'Verify New Password',
            ),
            'attributes' => array(
                'type' => 'password',
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

    /**
     * Returns the password that was entered into the form.
     *
     * @return string
     */
    public function getNewPassword()
    {
        return $this->get('newCredential')->getValue();
    }
}
