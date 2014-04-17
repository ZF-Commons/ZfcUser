<?php

namespace ZfcUserTest\Form\TestAsset;

use ZfcUser\Form\Base;
use ZfcUser\Options\RegistrationOptionsInterface;

class BaseExtension extends Base
{
    /**
     * @var RegistrationOptionsInterface
     */
    protected $registrationOptions;

    public function __construct(RegistrationOptionsInterface $options)
    {
        $this->setRegistrationOptions($options);
        parent::__construct(null);
    }

    /**
     * Set Regsitration Options
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
     * Get Regsitration Options
     *
     * @return RegistrationOptionsInterface
     */
    public function getRegistrationOptions()
    {
        return $this->registrationOptions;
    }
}
