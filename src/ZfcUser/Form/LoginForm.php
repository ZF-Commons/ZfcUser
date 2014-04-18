<?php
namespace ZfcUser\Form;

use Zend\Form\Form;
use Zend\Form\Element;

/**
 * Class LoginForm
 * @package ZfcUser\Form
 */
class LoginForm extends Form
{
    /**
     * @var bool
     */
    protected $initialized = false;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        /**
         * This is needed as ZF2 runs init() on every call for a shared form (prior to 2.3.1)
         * See: https://github.com/zendframework/zf2/pull/6132
         */
        if ($this->initialized) {
            return;
        }

        $this->initialized = true;

        $this->add([
            'name' => 'identity',
            'type' => 'ZfcUser\Form\Element\IdentityElement',
            'options' => [
                'label' => 'Identity',
            ]
        ]);

        $this->add([
            'name' => 'credential',
            'type' => 'Password',
            'options' => [
                'label' => 'Password',
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'type' => 'Button',
            'attributes' => [
                'type' => 'submit',
            ],
            'options' => [
                'label' => 'Sign In',
            ],
        ]);
    }
}
