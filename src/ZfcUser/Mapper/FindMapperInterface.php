<?php

namespace ZfcUser\Mapper;

use ZfcUser\Entity\UserInterface;

interface FindMapperInterface
{
    /**
     * Find an entity by id.
     *
     * @param mixed $id
     * @return UserInterface
     */
    public function find($id);

    /**
     * Find an entity by email.
     *
     * @param string $email
     * @return UserInterface
     */
    public function findByEmail($email);
}