<?php

namespace ZfcUser\Options;

/**
 * Interface for providing options to the {@see Controller\ForgottenPasswordController}
 * @author Tom Oram <tom@x2k.co.uk>
 */
interface ForgottenPasswordControllerOptionsInterface
{
    /**
     * Sets the value for enableForgottenPassword.
     *
     * @param bool $enableForgottenPassword
     */
    public function setEnableForgottenPassword($enableForgottenPassword);

    /**
     * Gets the value for enableForgottenPassword.
     *
     * @return bool
     */
    public function getEnableForgottenPassword();
}