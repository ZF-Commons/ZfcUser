<?php
namespace ZfcUser\Validator;

use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Crypt\Password\Bcrypt;
use Zend\Validator\AbstractValidator;

/**
 * Class VerifyPasswordValidator
 * @package ZfcUser\Validator
 */
class VerifyPasswordValidator extends AbstractValidator
{
    /**
     * Error codes
     * @const string
     */
    const INCORRECT_PASSWORD = 'incorrectPassword';

    /**
     * Error messages
     * @var array
     */
    protected $messageTemplates = array(
        self::INCORRECT_PASSWORD => 'Incorrect password',
    );

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
        parent::__construct();
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isValid($value)
    {
        // No need to accidentally expose the password
        $this->setValue(null);

        // Verify the password
        $bcrypt = new Bcrypt();
        if (!$bcrypt->verify($value, $this->authenticationService->getIdentity()->getPassword())) {
            $this->error(self::INCORRECT_PASSWORD);
            return false;
        }

        return true;
    }
}
