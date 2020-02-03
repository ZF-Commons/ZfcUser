<?php

namespace ZfcUser\Form;

use ZfcUser\Options\AuthenticationOptionsInterface;

class ChangeEmail extends ProvidesEventsForm
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
            'name' => 'newIdentity',
            'options' => array(
                'label' => 'New Email',
            ),
            'attributes' => array(
                'id' => 'newIdentity',
                'type' => 'text',
            ),
        ));

        $this->add(array(
            'name' => 'newIdentityVerify',
            'options' => array(
                'label' => 'Verify New Email',
            ),
            'attributes' => array(
                'id' => 'newIdentityVerify',
                'type' => 'text',
            ),
        ));

        $this->add(array(
            'name' => 'credential',
            'type' => 'password',
            'options' => array(
                'label' => 'Password',
            ),
            'attributes' => array(
                'id' => 'credential',
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
    }

    /**
     * Set Authentication-related Options
     *
     * @param AuthenticationOptionsInterface $authOptions
     * @return ChangeEmail
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
