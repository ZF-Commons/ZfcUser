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
     * @var RegistrationOptionsInterface
     */
    protected $registrationOptions;

    /**
     * @param null|int|string               $name                   Optional name for the element
     * @param RegistrationOptionsInterface  $registrationOptions    Options for this form
     */
    public function __construct($name = null, RegistrationOptionsInterface $registrationOptions)
    {
        $this->registrationOptions = $registrationOptions;
        parent::__construct($name);
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if ($this->registrationOptions->getEnableUsername()) {
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

        if ($this->registrationOptions->getUseRegistrationFormCaptcha()) {
            $this->add([
                'name' => 'captcha',
                'type' => 'Captcha',
                'options' => [
                    'label' => 'Please type the following text',
                    'captcha' => $this->registrationOptions->getFormCaptchaOptions(),
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
