<?php

namespace ZfcUser\Model;

interface UserMapperInterface
{
    public function persist(UserInterface $user);

    public function findByEmail($email);

    public function findByUsername($username);

    public function findById($id);
}
