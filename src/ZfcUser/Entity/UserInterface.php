<?php

namespace ZfcUser\Entity;

interface UserInterface
{
    /**
     * @param int $id
     * @return UserInterface
     */
    public function setId($id);

    /**
     * @return int
     */
    public function getId();

    /**
     * @param string $email
     * @return UserInterface
     */
    public function setEmail($email);

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @param string $password
     * @return UserInterface
     */
    public function setPassword($password);

    /**
     * @return string
     */
    public function getPassword();
}
