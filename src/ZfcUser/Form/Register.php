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
    public function __construct($name = null, RegistrationOptionsInterface $options)
    {
        $this->setFormOptions($options);
        parent::__construct($name);

        $this->remove('userId');
        if (!$this->getFormOptions()->getEnableUsername()) {
            $this->remove('username');
        }
        if (!$this->getFormOptions()->getEnableDisplayName()) {
            $this->remove('display_name');
        }
        if ($this->getFormOptions()->getUseFormCaptcha() && $this->captchaElement) {
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
    public function setFormOptions(RegistrationOptionsInterface $registrationOptions)
    {
        $this->registrationOptions = $registrationOptions;
        return $this;
    }

    /**
     * Get Registration Options
     *
     * @return RegistrationOptionsInterface
     */
    public function getFormOptions()
    {
        return $this->registrationOptions;
    }
}
