<?php
namespace ZfcUser\Form;

use Zend\Form\Form;
use ZfcUser\Options\RegistrationOptionsInterface;

/**
 * Class RegistrationForm
 * @package ZfcUser\Form
 */
class RegistrationForm extends Form
{
    /**
     * @var bool
     */
    protected $initialized = false;

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
        /**
         * This is needed as ZF2 runs init() on every call for a shared form
         * See: https://github.com/zendframework/zf2/pull/6132
         */
        if ($this->initialized) {
            return;
        }

        $this->initialized = true;

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

        if ($this->options->getEnableDisplayName()) {
            $this->add([
                'name' => 'display_name',
                'type' => 'Text',
                'options' => [
                    'label' => 'Display Name',
                ],
            ]);
        }

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

        if ($this->options->getUseRegistrationFormCaptcha()) {
            $this->add([
                'name' => 'captcha',
                'type' => 'Captcha',
                'options' => [
                    'label' => 'Please type the following text',
                    'captcha' => $this->options->getFormCaptchaOptions(),
                ],
            ]);
        }

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
