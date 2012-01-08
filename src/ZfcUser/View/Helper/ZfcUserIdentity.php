<?php

namespace EdpUser\View\Helper;

use Zend\View\Helper\AbstractHelper,
    Zend\Authentication\AuthenticationService;

class EdpUserIdentity extends AbstractHelper
{
    /**
     * @var AuthenticationService
     */
    protected $authService;

    /**
     * __invoke 
     * 
     * @access public
     * @return EdpUser\Model\UserInterface
     */
    public function __invoke()
    {
        if ($this->getAuthService()->hasIdentity()) {
            return $this->getAuthService()->getIdentity();
        } else {
            return false;
        }
    }

    /**
     * Get authService.
     *
     * @return AuthenticationService
     */
    public function getAuthService()
    {
        return $this->authService;
    }
 
    /**
     * Set authService.
     *
     * @param AuthenticationService $authService
     */
    public function setAuthService(AuthenticationService $authService)
    {
        $this->authService = $authService;
        return $this;
    }
}
