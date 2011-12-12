<?php

namespace EdpUser\View\Helper;

use Zend\View\Helper\AbstractHelper,
    EdpUser\Service\User as UserService;

class EdpUserService extends AbstractHelper
{
    /**
     * @var User
     */
    protected $userService;

    /**
     * __invoke 
     * 
     * @return UserService
     */
    public function __invoke()
    {
        return $this->getUserService();
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
