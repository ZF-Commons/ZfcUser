<?php

namespace EdpUser\Mapper;

use EdpUser\ModelBase\UserBase;

interface UserInterface
{
    public function persist(UserBase $user);

    public function findByEmail($email);

    public function findByUsername($username);

    public function getAuthAdapter($identity, $credential, $identityColumn);

    public function getEmailValidator();
}
