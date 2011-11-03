<?php

namespace EdpUser\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(name="user_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO") 
     */
    private $userId;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $email;

    /**
     * @ORM\Column(name="display_name", type="string", length=50)
     */
    private $displayName;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private $salt;

    /**
     * @ORM\Column(name="last_login", type="datetime")
     */
    private $lastLogin;

    /**
     * @ORM\Column(name="last_ip", type="integer")
     */
    private $lastIp;

    /**
     * @ORM\Column(name="register_time", type="datetime")
     */
    private $registerTime;

    /**
     * @ORM\Column(name="register_ip", type="integer")
     */
    private $registerIp;
 
    /**
     * Get userId.
     *
     * @return int userId
     */
    public function getUserId()
    {
        return $this->userId;
    }
 
    /**
     * Set userId.
     *
     * @param int $userId the value to be set
     * @return User
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }
 
    /**
     * Get username.
     *
     * @return string username
     */
    public function getUsername()
    {
        return $this->username;
    }
 
    /**
     * Set username.
     *
     * @param string $username the value to be set
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }
 
    /**
     * Get email.
     *
     * @return string email
     */
    public function getEmail()
    {
        return $this->email;
    }
 
    /**
     * Set email.
     *
     * @param string $email the value to be set
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }
 
    /**
     * Get displayName.
     *
     * @return string displayName
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }
 
    /**
     * Set displayName.
     *
     * @param string $displayName the value to be set
     * @return User
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
        return $this;
    }
 
    /**
     * Get password.
     *
     * @return string password
     */
    public function getPassword()
    {
        return $this->password;
    }
 
    /**
     * Set password.
     *
     * @param string $password the value to be set
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }
 
    /**
     * Get salt.
     *
     * @return string salt
     */
    public function getSalt()
    {
        return $this->salt;
    }
 
    /**
     * Set salt.
     *
     * @param string $salt the value to be set
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
        return $this;
    }
 
    /**
     * Get lastLogin.
     *
     * @return DateTime lastLogin
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }
 
    /**
     * Set lastLogin.
     *
     * @param DateTime $lastLogin the value to be set
     * @return User
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;
        return $this;
    }
 
    /**
     * Get lastIp.
     *
     * @TODO: Map custom IP field type with inet_pton() and inet_ntop()
     * @return lastIp
     */
    public function getLastIp()
    {
        return $this->lastIp;
    }
 
    /**
     * Set lastIp.
     *
     * @TODO: Map custom IP field type with inet_pton() and inet_ntop()
     * @param $lastIp the value to be set
     * @return User
     */
    public function setLastIp($lastIp)
    {
        $this->lastIp = $lastIp;
        return $this;
    }
 
    /**
     * Get registerTime.
     *
     * @return DateTime registerTime
     */
    public function getRegisterTime()
    {
        return $this->registerTime;
    }
 
    /**
     * Set registerTime.
     *
     * @param DateTime $registerTime the value to be set
     * @return User
     */
    public function setRegisterTime($registerTime)
    {
        $this->registerTime = $registerTime;
        return $this;
    }
 
    /**
     * Get registerIp.
     *
     * @TODO: Map custom IP field type with inet_pton() and inet_ntop()
     * @return registerIp
     */
    public function getRegisterIp()
    {
        return $this->registerIp;
    }
 
    /**
     * Set registerIp.
     *
     * @TODO: Map custom IP field type with inet_pton() and inet_ntop()
     * @param $registerIp the value to be set
     * @return User
     */
    public function setRegisterIp($registerIp)
    {
        $this->registerIp = $registerIp;
        return $this;
    }
}
