<?php

namespace ZfcUser\Options;

interface RegistrationOptionsInterface
{
    public function setEnableDisplayName($enableDisplayName);

    public function getEnableDisplayName();

    public function setEnableUsername($enableUsername);

    public function getEnableUsername();

    public function setUseRegistrationFormCaptcha($useRegistrationFormCaptcha);

    public function getUseRegistrationFormCaptcha();

    public function setLoginAfterRegistration($loginAfterRegistration);

    public function getLoginAfterRegistration();

    public function setRequireActivation($requireActivation);

    public function getRequireActivation();

    public function setEnableRegistration($enableRegistration);

    public function getEnableRegistration();
}
