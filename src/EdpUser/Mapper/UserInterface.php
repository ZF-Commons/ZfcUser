<?php

namespace EdpUser\Mapper;

use EdpUser\Model\User as UserModel;

interface UserInterface
{
    public function persist(UserModel $user);

    public function findByEmail($email);

    public function findByUsername($username);

    public function getAuthAdapter($identity, $credential, $identityColumn);

    public function getEmailValidator();
}
