<?php

namespace ZfcUser\Model;

use DateTime,
    ZfcBase\Model\AbstractModel;

class User extends AbstractModel implements UserInterface
{
    protected $user_id;

    protected $username;

    protected $email;

    protected $display_name;

    protected $password;

    protected $last_login;

    protected $last_ip;

    protected $register_time;

    protected $register_ip;

    protected $active;

    protected $enabled;
 
    /**
     * Get user_id.
     *
     * @return int user_id
     */
    public function getUserId()
    {
        return $this->user_id;
    }
 
    /**
     * Set user_id.
     *
     * @param int $userId the value to be set
     * @return UserBase
     */
    public function setUserId($userId)
    {
        $this->user_id = (int) $userId;
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
     * @return UserBase
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
     * @return UserBase
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }
 
    /**
     * Get display_name.
     *
     * @return string display_name
     */
    public function getDisplayName()
    {
        if ($this->display_name !== null) {
            return $this->display_name;
        } elseif ($this->username !== null) {
            return $this->username;
        } elseif ($this->email !== null) {
            return $this->email;
        }
        return null;
    }
 
    /**
     * Set display_name.
     *
     * @param string $displayName the value to be set
     * @return UserBase
     */
    public function setDisplayName($displayName)
    {
        $this->display_name = $displayName;
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
     * @return UserBase
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }
 
    /**
     * Get last_login.
     *
     * @return DateTime last_login
     */
    public function getLastLogin()
    {
        return $this->last_login;
    }
 
    /**
     * Set last_login.
     *
     * @param mixed $lastLogin the value to be set
     * @return UserBase
     */
    public function setLastLogin($lastLogin)
    {
        if ($lastLogin instanceof DateTime) {
            $this->last_login = $lastLogin;
        } else {
            $this->last_login = new DateTime($lastLogin);
        }
        return $this;
    }
 
    /**
     * Get last_ip.
     *
     * @TODO: Map custom IP field type with inet_pton() and inet_ntop()
     * @param bool $long
     * @return last_ip
     */
    public function getLastIp($long = false)
    {
        if (true === $long) {
            return $this->last_ip;
        }
        return long2ip($this->last_ip);
    }
 
    /**
     * Set last_ip.
     *
     * @TODO: Map custom IP field type with inet_pton() and inet_ntop()
     * @param $lastIp the value to be set
     * @return UserBase
     */
    public function setLastIp($lastIp)
    {
        $this->last_ip = ip2long($lastIp);
        return $this;
    }
 
    /**
     * Get register_time.
     *
     * @return DateTime register_time
     */
    public function getRegisterTime()
    {
        return $this->register_time;
    }
 
    /**
     * Set register_time.
     *
     * @param string $registerTime the value to be set
     * @return UserBase
     */
    public function setRegisterTime($registerTime)
    {
        if ($registerTime instanceof DateTime) {
            $this->register_time = $registerTime;
        } else {
            $this->register_time = new DateTime($registerTime);
        }
        return $this;
    }
 
    /**
     * Get register_ip.
     *
     * @TODO: Map custom IP field type with inet_pton() and inet_ntop()
     * @param bool $long
     * @return register_ip
     */
    public function getRegisterIp($long = false)
    {
        if (true === $long) {
            return $this->register_ip;
        }
        return long2ip($this->register_ip);
    }
 
    /**
     * Set register_ip.
     *
     * @TODO: Map custom IP field type with inet_pton() and inet_ntop()
     * @param $registerIp the value to be set
     * @return UserBase
     */
    public function setRegisterIp($registerIp)
    {
        $this->register_ip = ip2long($registerIp);
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
