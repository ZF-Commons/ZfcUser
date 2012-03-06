<?php

namespace ZfcUser\Model\Mapper;


interface User
{
    public function persist($user);

    public function findByEmail($email);

    public function findByUsername($username);

    public function findById($id);
}
