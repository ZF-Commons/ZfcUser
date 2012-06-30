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
    protected $options;

    /**
     * @param string|null $name
     * @param RegistrationOptionsInterface $options
     */
    public function __construct($name = null, RegistrationOptionsInterface $options)
    {
        $this->setOptions($options);
        parent::__construct($name);

        $this->remove('userId');
        if (!$this->getOptions()->getEnableUsername()) {
            $this->remove('username');
        }
        if (!$this->getOptions()->getEnableDisplayName()) {
            $this->remove('displayName');
        }
        if ($this->getOptions()->getUseRegistrationFormCaptcha() && $this->captchaElement) {
            $this->add($this->captchaElement, array('name'=>'captcha'));
        }
        $this->get('submit')->setAttribute('Label', 'Register');
    }

    public function setCaptchaElement(Captcha $captchaElement)
    {
        $this->captchaElement= $captchaElement;
    }

    /**
     * set options
     *
     * @param RegistrationOptionsInterface $options
     * @return Register
     */
    public function setOptions(RegistrationOptionsInterface $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * get options
     *
     * @return RegistrationOptionsInterface
     */
    public function getOptions()
    {
        return $this->options;
    }


}
