<?php

namespace ZfcUser\View\Helper;

use ZfcUser\Service\User as UserService;
use Zend\View\Helper\AbstractHelper;

class ZfcUserImpersonatorIdentity extends AbstractHelper
{
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * __invoke
     *
     * @access public
     * @return ZfcUser\Model\UserInterface
     */
    public function __invoke()
    {
        if ($this->getUserService()->isImpersonated()) {
            return $this->getUserService()->getStorageForImpersonator()->read();
        } else {
            return false;
        }
    }

    /**
     * Get userService.
     *
     * @return ZfcUser\Service\User
     */
    public function getUserService()
    {
        return $this->userService;
    }

    /**
     * Set userService.
     *
     * @param ZfcUser\Service\User $userService
     */
    public function setUserService(UserService $userService)
    {
        $this->userService = $userService;
        return $this;
    }
}
