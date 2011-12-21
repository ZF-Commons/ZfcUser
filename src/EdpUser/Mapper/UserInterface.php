<?php

namespace EdpUser\Mapper;

use EdpUser\Model\UserInterface as UserModelInterface;

interface UserInterface
{
    public function persist(UserModelInterface $user);

    public function findByEmail($email);

    public function findByUsername($username);

    public function getAuthAdapter($identity, $credential, $identityColumn);

    public function getEmailValidator();
}
