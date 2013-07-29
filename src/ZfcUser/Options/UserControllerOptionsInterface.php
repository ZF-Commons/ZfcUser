<?php

namespace ZfcUser\Options;

use ZfcUser\Options\AuthenticationOptionsInterface;

interface UserControllerOptionsInterface
{
    /**
     * set use redirect param if present
     *
     * @param bool $useRedirectParameterIfPresent
     */
    public function setUseRedirectParameterIfPresent($useRedirectParameterIfPresent);

    /**
     * get use redirect param if present
     *
     * @return bool
     */
    public function getUseRedirectParameterIfPresent();
    
    /**
     * get change password route
     *
     * @return string
     */
    public function getChangePasswordRoute();
    
    /**
     * get login route
     *
     * @return string
     */
    public function getLoginRoute();
    
    /**
     * get logout route
     *
     * @return string
     */
    public function getLogoutRoute();
    
    /**
     * get register route
     *
     * @return string
     */
    public function getRegisterRoute();
    
    /**
     * get change email route
     *
     * @return string
     */
    public function getChangeEmailRoute();
    
    /**
     * get controller name
     *
     * @return string
     */
    public function getControllerName();
}
