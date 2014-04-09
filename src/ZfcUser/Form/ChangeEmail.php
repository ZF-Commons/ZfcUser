<?php

namespace ZfcUser\Form;

use ZfcBase\Form\ProvidesEventsForm;
use ZfcUser\Options\RegistrationOptionsInterface;
use ZfcUser\Options\AuthenticationOptionsInterface;

class ChangeEmail extends ProvidesEventsForm
{

    protected $translator;	

    public function __construct($name = null, AuthenticationOptionsInterface $options)
    {
        $this->setAuthenticationOptions($options);
        parent::__construct($name);

	$translator = new \Zend\I18n\Translator\Translator();

        $this->add(array(
            'name' => 'identity',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'newIdentity',
            'options' => array(
                'label' => $translator->translate('New Email'),
            ),
            'attributes' => array(
                'type' => 'text',
            ),
        ));

        $this->add(array(
            'name' => 'newIdentityVerify',
            'options' => array(
                'label' => $translator->translate('Verify New Email'),
            ),
            'attributes' => array(
                'type' => 'text',
            ),
        ));

        $this->add(array(
            'name' => 'credential',
            'options' => array(
                'label' => $translator->translate('Password'),
            ),
            'attributes' => array(
                'type' => 'password',
            ),
        ));

        $this->getEventManager()->trigger('init', $this);
    }

    /**
     * Set Authentication-related Options
     *
     * @param AuthenticationOptionsInterface $authOptions
     * @return Login
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
