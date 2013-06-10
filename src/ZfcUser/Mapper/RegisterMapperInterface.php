<?php

namespace ZfcUser\Mapper;

use ZfcUser\Entity\UserInterface;

interface RegisterMapperInterface
{
    /**
     * Register a user.
     *
     * @param UserInterface $user
     * @return UserInterface
     */
    public function register(UserInterface $user);
}