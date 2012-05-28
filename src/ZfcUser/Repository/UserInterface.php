<?php

namespace ZfcUser\Repository;

use ZfcBase\Repository\RepositoryInterface;

interface UserInterface extends RepositoryInterface
{
    public function findByEmail($email);

    public function findByUsername($username);

}
