<?php

namespace ZfcUser\Form;

use Zend\Form\Form;
use Zend\Form\Element\Csrf;
use ZfcBase\Form\ProvidesEventsForm;
use ZfcUser\Options\AuthenticationOptionsInterface;
use ZfcUser\Module as ZfcUser;

class Login extends ProvidesEventsForm
{
    /**
     * @var AuthenticationOptionsInterface
     */
    protected $options;

    public function __construct($name = null, AuthenticationOptionsInterface $options)
    {
        $this->setOptions($options);
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
        foreach ($this->getOptions()->getAuthIdentityFields() as $mode) {
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

        // @todo: Fix this
        // 1) getValidator() is a protected method
        // 2) i don't believe the login form is actually being validated by the login action
        // (but keep in mind we don't want to show invalid username vs invalid password or
        // anything like that, it should just say "login failed" without any additional info)
        //$csrf = new Csrf('csrf');
        //$csrf->getValidator()->setTimeout($options->getLoginFormTimeout());
        //$this->add($csrf);

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'label' => 'Submit',
                'type' => 'submit'
            ),
        ));

        $this->getEventManager()->trigger('init', $this);
    }

    /**
     * set options
     *
     * @param AuthenticationOptionsInterface $options
     * @return Login
     */
    public function setOptions(AuthenticationOptionsInterface $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * get options
     *
     * @return AuthenticationOptionsInterface
     */
    public function getOptions()
    {
        return $this->options;
    }
}
