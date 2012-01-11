<?php

namespace ZfcUser\Mapper;

use ZfcUser\Model\UserInterface as UserModelInterface;

interface UserInterface
{
    public function persist(UserModelInterface $user);

    public function findByEmail($email);

    public function findByUsername($username);

    public function findById($id);
}
