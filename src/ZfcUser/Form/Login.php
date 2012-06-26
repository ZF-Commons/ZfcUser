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
