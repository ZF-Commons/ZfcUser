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
     * {@inheritdoc}
     */
    public function init()
    {
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
