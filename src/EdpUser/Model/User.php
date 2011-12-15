<?php

namespace EdpUser\Model;

use DateTime,
    EdpCommon\Model\ModelAbstract;

class User extends ModelAbstract implements UserInterface
{
    private $userId;

    private $username;

    private $email;

    private $displayName;

    private $password;

    private $lastLogin;

    private $lastIp;

    private $registerTime;

    private $registerIp;

    private $active;

    private $enabled;
 
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
        if ($this->displayName !== null) {
            return $this->displayName;
        } elseif ($this->username !== null) {
            return $this->username;
        } elseif ($this->email !== null) {
            return $this->email;
        }
        return null;
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
     * @param mixed $lastLogin the value to be set
     * @return User
     */
    public function setLastLogin($lastLogin)
    {
        if ($lastLogin instanceof DateTime) {
            $this->lastLogin = $lastLogin;
        } else {
            $this->lastLogin = new DateTime($lastLogin);
        }
        return $this;
    }
 
    /**
     * Get lastIp.
     *
     * @TODO: Map custom IP field type with inet_pton() and inet_ntop()
     * @param bool $long
     * @return lastIp
     */
    public function getLastIp($long = false)
    {
        if (true === $long) {
            return $this->lastIp;
        }
        return long2ip($this->lastIp);
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
        $this->lastIp = ip2long($lastIp);
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
     * @param string $registerTime the value to be set
     * @return User
     */
    public function setRegisterTime($registerTime)
    {
        if ($registerTime instanceof DateTime) {
            $this->registerTime = $registerTime;
        } else {
            $this->registerTime = new DateTime($registerTime);
        }
        return $this;
    }
 
    /**
     * Get registerIp.
     *
     * @TODO: Map custom IP field type with inet_pton() and inet_ntop()
     * @param bool $long
     * @return registerIp
     */
    public function getRegisterIp($long = false)
    {
        if (true === $long) {
            return $this->registerIp;
        }
        return long2ip($this->registerIp);
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
        $this->registerIp = ip2long($registerIp);
        return $this;
    }
 
    /**
     * Get active.
     *
     * @return bool active
     */
    public function getActive()
    {
        return $this->active;
    }
 
    /**
     * Set active.
     *
     * @param bool $active the value to be set
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }
 
    /**
     * Get enabled.
     *
     * @return bool enabled
     */
    public function getEnabled()
    {
        return $this->enabled;
    }
 
    /**
     * Set enabled.
     *
     * @param bool $enabled the value to be set
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
        return $this;
    }
}
