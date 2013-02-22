<?php

namespace ZfcUser\View\Helper;

use ZfcUser\Entity\UserInterface as User;
use ZfcUser\Service\User as UserService;
use Zend\View\Helper\AbstractHelper;

class ZfcUserImpersonatorDisplayName extends ZfcUserDisplayName
{
    /**
     * @var ZfcUser\Service\User
     */
    protected $userService;

    /**
     * __invoke
     *
     * @access public
     * @return String
     */
    public function __invoke()
    {
        if ($this->getUserService()->isImpersonated()) {
            $user = $this->getUserService()->getStorageForImpersonator()->read();
            if (!$user instanceof User) {
                throw new \ZfcUser\Exception\DomainException(
                    '$user is not an instance of UserInterface', 500
                );
            }
        } else {
            return false;
        }

        return parent::__invoke($user);
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
