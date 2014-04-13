<?php
namespace ZfcUser\Form;

use Zend\Form\Form;
use ZfcUser\Options\RegistrationOptionsInterface;

/**
 * Class Registration
 * @package ZfcUser\Form
 */
class Registration extends Form
{
    /**
     * @var RegistrationOptionsInterface
     */
    protected $options;

    /**
     * @param null $name
     * @param RegistrationOptionsInterface $options
     */
    public function __construct($name = null, RegistrationOptionsInterface $options)
    {
        $this->options = $options;
        parent::__construct($name);
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if ($this->options->getEnableUsername()) {
            $this->add([
                'name' => 'username',
                'type' => 'Text',
                'options' => [
                    'label' => 'Username',
                ],
            ]);
        }

        $this->add([
            'name' => 'email',
            'type' => 'Email',
            'options' => [
                'label' => 'Email',
            ],
        ]);

        $this->add([
            'name' => 'password',
            'type' => 'Password',
            'options' => [
                'label' => 'Password',
            ],
        ]);

        $this->add([
            'name' => 'passwordVerify',
            'type' => 'Password',
            'options' => [
                'label' => 'Password Verify',
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'type' => 'Button',
            'attributes' => [
                'type' => 'submit',
            ],
            'options' => [
                'label' => 'Register',
            ],
        ]);
    }
}
