<?php

namespace ZfcUser\Model\Mapper;

use ZfcUser\Model\User as UserModel;

interface User
{
    public function persist(UserModel $user);

    public function findByEmail($email);

    public function findByUsername($username);

    public function findById($id);
}
