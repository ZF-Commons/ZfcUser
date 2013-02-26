<?php

namespace ZfcUser\Hash;

use ZfcUser\Entity\UserInterface;

/**
 * Interface for classes which will generate a hash which can be
 * used to validate a link.
 *
 * @author Tom Oram <tom@x2k.co.uk>
 */
interface UserHashInterface
{
    /**
     * Creates a hash from the user object.
     *
     * @param UserInterface $user
     * @return string
     */
    public function createHash(UserInterface $user);

    /**
     * Checks the given hash is valid for the user.
     *
     * @param UserInterface $user
     * @param string        $hash
     * @return boolean
     */
    public function checkHash(UserInterface $user, $hash);
}
