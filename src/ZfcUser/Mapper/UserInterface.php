<?php

namespace ZfcUser\Mapper;

interface UserInterface
{
    /**
     * @param $email
     * @return \ZfcUser\Entity\UserInterface
     */
    public function findByEmail($email);

    /**
     * @param string $username
     * @return \ZfcUser\Entity\UserInterface
     */
    public function findByUsername($username);

    /**
     * @param string|int $id
     * @return \ZfcUser\Entity\UserInterface
     */
    public function findById($id);

    /**
     * @param \ZfcUser\Entity\UserInterface $user
     */
    public function insert(\ZfcUser\Entity\UserInterface $user);

    /**
     * @param \ZfcUser\Entity\UserInterface $user
     */
    public function update(\ZfcUser\Entity\UserInterface $user);
}
