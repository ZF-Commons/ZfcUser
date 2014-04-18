<?php

namespace ZfcUser\Form;

use Zend\Form\Element\Captcha as Captcha;
use ZfcUser\Options\RegistrationOptionsInterface;

class Register extends Base
{
    protected $captchaElement= null;

    /**
     * @var RegistrationOptionsInterface
     */
    protected $registrationOptions;

    /**
     * @param string|null $name
     * @param RegistrationOptionsInterface $options
     */
    public function __construct($name, RegistrationOptionsInterface $options)
    {
        $this->setRegistrationOptions($options);
        parent::__construct($name);

        $this->remove('userId');
        if (!$this->getRegistrationOptions()->getEnableUsername()) {
            $this->remove('username');
        }
        if (!$this->getRegistrationOptions()->getEnableDisplayName()) {
            $this->remove('display_name');
        }
        if ($this->getRegistrationOptions()->getUseRegistrationFormCaptcha() && $this->captchaElement) {
            $this->add($this->captchaElement, array('name'=>'captcha'));
        }
        $this->get('submit')->setLabel('Register');
        $this->getEventManager()->trigger('init', $this);
    }

    public function setCaptchaElement(Captcha $captchaElement)
    {
        $this->captchaElement= $captchaElement;
    }

    /**
     * Set Registration Options
     *
     * @param RegistrationOptionsInterface $registrationOptions
     * @return Register
     */
    public function setRegistrationOptions(RegistrationOptionsInterface $registrationOptions)
    {
        $this->registrationOptions = $registrationOptions;
        return $this;
    }

    /**
     * Get Registration Options
     *
     * @return RegistrationOptionsInterface
     */
    public function getRegistrationOptions()
    {
        return $this->registrationOptions;
    }
}
