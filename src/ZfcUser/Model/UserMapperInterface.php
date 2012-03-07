<?php

namespace ZfcUser\Model;

interface UserMapperInterface
{
    public function persist(User $user);

    public function findByEmail($email);

    public function findByUsername($username);

    public function findById($id);
}
