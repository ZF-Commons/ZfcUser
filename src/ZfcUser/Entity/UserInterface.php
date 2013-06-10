<?php

namespace ZfcUser\Entity;

use DateTime;

interface UserInterface
{
    /**
     * @param string $displayName
     * @return UserInterface
     */
    public function setDisplayName($displayName);

    /**
     * @return string
     */
    public function getDisplayName();

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
     * @param int $id
     * @return UserInterface
     */
    public function setId($id);

    /**
     * @return int
     */
    public function getId();

    /**
     * @param Datetime $joinDate
     * @return UserInterface
     */
    public function setJoinDate(DateTime $joinDate);

    /**
     * @return Datetime
     */
    public function getJoinDate();

    /**
     * @param Datetime $loginDate
     * @return UserInterface
     */
    public function setLoginDate(DateTime$loginDate);

    /**
     * @return Datetime
     */
    public function getLoginDate();

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