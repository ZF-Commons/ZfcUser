<?php

namespace EdpUser\View\Helper;

use Zend\View\Helper\AbstractHelper,
    EdpUser\Service\User;

class UserService extends AbstractHelper
{
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * __invoke 
     * 
     * @return User
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
     * @return User
     */
    public function getUserService()
    {
        return $this->userService;
    }
 
    /**
     * Set userService.
     *
     * @param User $userService the value to be set
     */
    public function setUserService(User $userService)
    {
        $this->userService = $userService;
        return $this;
    }
}
