<?php

namespace ZfcUser\Storage;

use ZfcUser\Entity\UserInterface;

interface AdapterInterface
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

    /**
     * Register an entity.
     *
     * @param UserInterface $user
     * @return mixed
     */
    public function register(UserInterface $user);
}