<?php
namespace ZfcUser\View\Helper;

use Zend\Authentication\AuthenticationServiceInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * Class IdentityHelper
 * @package ZfcUser\View\Helper
 */
class IdentityHelper extends AbstractHelper
{
    /**
     * @var AuthenticationServiceInterface
     */
    protected $authenticationService;

    /**
     * @param AuthenticationServiceInterface $authenticationService
     */
    public function __construct(AuthenticationServiceInterface $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    /**
     * @return bool|\ZfcUser\Entity\UserInterface
     */
    public function __invoke()
    {
        if ($this->authenticationService->hasIdentity()) {
            return $this->authenticationService->getIdentity();
        }

        return false;
    }
}
