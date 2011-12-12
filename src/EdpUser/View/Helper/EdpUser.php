<?php

namespace EdpUser\View\Helper;

use Zend\View\Helper\AbstractHelper,
    EdpUser\Service\User as UserService;

class EdpUser extends AbstractHelper
{
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * __invoke 
     * 
     * @access public
     * @return EdpUser\ModelBase\UserInterface
     */
    public function __invoke()
    {
        if ($this->getUserService()->getAuthService()->hasIdentity()) {
            return $this->getUserService()->getAuthService()->getIdentity();
        } else {
            return false;
        }
    }

    public function getAuth()
    {
        return $this->getUserService()->getAuthService();
    }
 
    /**
     * Get userService.
     *
     * @return UserService
     */
    public function getUserService()
    {
        return $this->userService;
    }
 
    /**
     * Set userService.
     *
     * @param UserService $userService the value to be set
     */
    public function setUserService(UserService $userService)
    {
        $this->userService = $userService;
        return $this;
    }
}
