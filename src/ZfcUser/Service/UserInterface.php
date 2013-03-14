<?php

namespace ZfcUser\Service;

use ZfcUser\Entity\UserInterface as UserEntityInterface;

interface UserInterface
{
    /**
     * registers the user.
     *
     * @param  UserEntityInterface $user
     * @return boolean
     * @todo Check if the insert succeeds
     */
    public function register(UserEntityInterface $user);

    /**
     * Change the current user's password
     *
     * @param  string $oldPass
     * @param  string $newPass
     * @return boolean
     */
    public function changePassword($oldPass, $newPass);

    /**
     * Change the current users email address.
     *
     * @param  string $credential
     * @param  string $newEmail
     * @return boolean
     */
    public function changeEmail($credential, $newEmail);
}
