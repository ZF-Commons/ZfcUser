<?php

namespace ZfcUser\Entity;

use DateTime;

class User implements UserInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $displayName;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var DateTime
     */
    protected $joinDate;

    /**
     * @var DateTime
     */
    protected $loginDate;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->joinDate  = new DateTime();
        $this->loginDate = new DateTime();
    }

    /**
     * @param string $displayName
     * @return \ZfcUser\Entity\UserInterface
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
        return $this;
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @param string $email
     * @return \ZfcUser\Entity\UserInterface
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param int $id
     * @return \ZfcUser\Entity\UserInterface
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Datetime $joinDate
     * @return \ZfcUser\Entity\UserInterface
     */
    public function setJoinDate(DateTime $joinDate)
    {
        $this->joinDate = $joinDate;
        return $this;
    }

    /**
     * @return Datetime
     */
    public function getJoinDate()
    {
        return $this->joinDate;
    }

    /**
     * @param Datetime $loginDate
     * @return \ZfcUser\Entity\UserInterface
     */
    public function setLoginDate(DateTime$loginDate)
    {
        $this->loginDate = $loginDate;
        return $this;
    }

    /**
     * @return Datetime
     */
    public function getLoginDate()
    {
        return $this->loginDate;
    }

    /**
     * @param string $password
     * @return \ZfcUser\Entity\UserInterface
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
}