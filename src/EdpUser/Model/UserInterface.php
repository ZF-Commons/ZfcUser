<?php

namespace EdpUser\Model;

interface UserInterface
{
    /**
     * Get userId.
     *
     * @return int userId
     */
    public function getUserId();
 
    /**
     * Set userId.
     *
     * @param int $userId the value to be set
     * @return User
     */
    public function setUserId($userId);
 
    /**
     * Get username.
     *
     * @return string username
     */
    public function getUsername();
 
    /**
     * Set username.
     *
     * @param string $username the value to be set
     * @return User
     */
    public function setUsername($username);
 
    /**
     * Get email.
     *
     * @return string email
     */
    public function getEmail();
 
    /**
     * Set email.
     *
     * @param string $email the value to be set
     * @return User
     */
    public function setEmail($email);
 
    /**
     * Get displayName.
     *
     * @return string displayName
     */
    public function getDisplayName();
 
    /**
     * Set displayName.
     *
     * @param string $displayName the value to be set
     * @return User
     */
    public function setDisplayName($displayName);
 
    /**
     * Get password.
     *
     * @return string password
     */
    public function getPassword();
 
    /**
     * Set password.
     *
     * @param string $password the value to be set
     * @return User
     */
    public function setPassword($password);
 
    /**
     * Get lastLogin.
     *
     * @return DateTime lastLogin
     */
    public function getLastLogin();
 
    /**
     * Set lastLogin.
     *
     * @param mixed $lastLogin the value to be set
     * @return User
     */
    public function setLastLogin($lastLogin);
 
    /**
     * Get lastIp.
     *
     * @TODO: Map custom IP field type with inet_pton() and inet_ntop()
     * @param bool $long
     * @return lastIp
     */
    public function getLastIp($long = false);
 
    /**
     * Set lastIp.
     *
     * @TODO: Map custom IP field type with inet_pton() and inet_ntop()
     * @param $lastIp the value to be set
     * @return User
     */
    public function setLastIp($lastIp);
 
    /**
     * Get registerTime.
     *
     * @return DateTime registerTime
     */
    public function getRegisterTime();
 
    /**
     * Set registerTime.
     *
     * @param string $registerTime the value to be set
     * @return User
     */
    public function setRegisterTime($registerTime);
 
    /**
     * Get registerIp.
     *
     * @TODO: Map custom IP field type with inet_pton() and inet_ntop()
     * @param bool $long
     * @return registerIp
     */
    public function getRegisterIp($long = false);
 
    /**
     * Set registerIp.
     *
     * @TODO: Map custom IP field type with inet_pton() and inet_ntop()
     * @param $registerIp the value to be set
     * @return User
     */
    public function setRegisterIp($registerIp);
 
    /**
     * Get active.
     * 
     * @return bool
     */
    public function getActive();
 
    /**
     * Set active.
     *
     * @param bool $active the value to be set
     */
    public function setActive($active);
 
    /**
     * Get enabled.
     *
     * @return bool enabled
     */
    public function getEnabled();
 
    /**
     * Set enabled.
     *
     * @param bool $enabled the value to be set
     */
    public function setEnabled($enabled);
}
