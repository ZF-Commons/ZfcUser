<?php

namespace ZfcUser\Form;

use ZfcUser\Options\AuthenticationOptionsInterface;

class ChangePassword extends ProvidesEventsForm
{
    /**
     * @var AuthenticationOptionsInterface
     */
    protected $authOptions;

    public function __construct($name, AuthenticationOptionsInterface $options)
    {
        $this->setAuthenticationOptions($options);

        parent::__construct($name);

        $this->add(array(
            'name' => 'identity',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'id' => 'identity',
                'type' => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'credential',
            'type' => 'password',
            'options' => array(
                'label' => 'Current Password',
            ),
            'attributes' => array(
                'id' => 'credential',
                'type' => 'password',
            ),
        ));

        $this->add(array(
            'name' => 'newCredential',
            'options' => array(
                'label' => 'New Password',
            ),
            'attributes' => array(
                'id' => 'newCredential',
                'type' => 'password',
            ),
        ));

        $this->add(array(
            'name' => 'newCredentialVerify',
            'type' => 'password',
            'options' => array(
                'label' => 'Verify New Password',
            ),
            'attributes' => array(
                'id' => 'newCredentialVerify',
                'type' => 'password',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'value' => 'Submit',
                'type'  => 'submit'
            ),
        ));

        $this->getEventManager()->trigger('init', $this);
    }

    /**
     * Set Authentication-related Options
     *
     * @param AuthenticationOptionsInterface $authOptions
     * @return ChangePassword
     */
    public function setAuthenticationOptions(AuthenticationOptionsInterface $authOptions)
    {
        $this->authOptions = $authOptions;

        return $this;
    }

    /**
     * Get Authentication-related Options
     *
     * @return AuthenticationOptionsInterface
     */
    public function getAuthenticationOptions()
    {
        return $this->authOptions;
    }
}
