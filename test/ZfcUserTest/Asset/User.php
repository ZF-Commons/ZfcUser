<?php

namespace ZfcUserTest\Asset;

use DateTime;
use ZfcUser\Entity\UserInterface;

class User implements UserInterface
{
    /**
     * @param string $displayName
     * @return UserInterface
     */
    public function setDisplayName($displayName)
    {
        return $this;
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return 'test user';
    }

    /**
     * @param string $email
     * @return UserInterface
     */
    public function setEmail($email)
    {
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return 'test@user.com';
    }

    /**
     * @param int $id
     * @return UserInterface
     */
    public function setId($id)
    {
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return 1;
    }

    /**
     * @param Datetime $joinDate
     * @return UserInterface
     */
    public function setJoinDate(DateTime $joinDate)
    {
        return $this;
    }

    /**
     * @return Datetime
     */
    public function getJoinDate()
    {
        return new DateTime();
    }

    /**
     * @param Datetime $loginDate
     * @return UserInterface
     */
    public function setLoginDate(DateTime $loginDate)
    {
        return $this;
    }

    /**
     * @return Datetime
     */
    public function getLoginDate()
    {
        return new DateTime();
    }

    /**
     * @param string $password
     * @return UserInterface
     */
    public function setPassword($password)
    {
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return 'password';
    }
}