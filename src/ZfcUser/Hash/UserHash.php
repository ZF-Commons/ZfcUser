<?php

namespace ZfcUser\Hash;

use Zend\Crypt\Hash;
use ZfcUser\Entity\UserInterface;

class UserHash implements UserHashInterface
{
    /**
     * @var string
     */
    protected $algorithm;

    /**
     * Some extra secret information to hash.
     *
     * @var string
     */
    protected $secret;

    /**
     * Stores the hashing algorithm.
     *
     * @param string $algorithm
     */
    public function __construct($algorithm, $secret)
    {
        // @todo Could throw an exception if the algorithm is not supported.

        $this->algorithm = (string) $algorithm;
        $this->secret = (string) $secret;
    }

    /**
     * Takes the parts of the user and makes a string.
     * 
     * The password is included so that was the password is changed
     * the link is no longer valid.
     *
     * A more secure solution would be to store either an extra secret
     * in the user record and/or to timeout the link but these options
     * would require additional database fields so maybe an extension
     * to this system could be made to accomodate that.
     *
     * @param UserInterface $user
     * @return string
     */
    protected function userToString(UserInterface $user)
    {
        return sprintf(
            '%s:%s:%s:%s',
            $user->getId(),
            $user->getEmail(),
            $user->getPassword(),
            $this->secret
        );
    }

    /**
     * {@inheritDoc}
     *
     * @param UserInterface $user
     * @return string
     */
    public function createHash(UserInterface $user)
    {
        return Hash::compute($this->algorithm, $this->userToString($user));
    }

    /**
     * {@inheritDoc}
     *
     * @param UserInterface $user
     * @param string        hash
     * @return boolean
     */
    public function checkHash(UserInterface $user, $hash)
    {
        return $hash === $this->createHash($user);
    }
}
