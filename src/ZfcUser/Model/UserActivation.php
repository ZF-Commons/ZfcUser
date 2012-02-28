<?php

namespace ZfcUser\Model;

interface UserActivation
{
    /**
     * Get id.
     *
     * @return int id
     */
    public function getId();

    /**
     * Set id.
     *
     * @param int $id the value to be set
     * @return UserActivation
     */
    public function setId($id);

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
     * @return UserActivation
     */
    public function setUserId($userId);

    /**
     * Get token.
     *
     * @return string
     */
    public function getToken();

    /**
     * Set token.
     *
     * @param string $token the value to be set
     * @return UserActivation
     */
    public function setToken($token);

    /**
     * Get requestTime.
     *
     * @return \DateTime registerTime
     */
    public function getRequestTime();

    /**
     * Set requestTime.
     *
     * @param string|\DateTime $requestTime the value to be set
     * @return UserActivation
     */
    public function setRequestTime($requestTime);

    /**
     * Get validTo.
     *
     * @return \DateTime validTo
     */
    public function getValidTo();

    /**
     * Set validTo.
     *
     * @param string|\DateTime $validTo the value to be set
     * @return UserActivation
     */
    public function setValidTo($validTo);

    /**
     * Get requestIp.
     *
     * @TODO: Map custom IP field type with inet_pton() and inet_ntop()
     * @param bool $long
     * @return string|int
     */
    public function getRequestIp($long = false);

    /**
     * Set requestIp.
     *
     * @TODO: Map custom IP field type with inet_pton() and inet_ntop()
     * @param string $requestIp the value to be set
     * @return UserActivation
     */
    public function setRequestIp($requestIp);

    /**
     * Get confirmTime.
     *
     * @return \DateTime registerTime
     */
    public function getConfirmTime();

    /**
     * Set confirmTime.
     *
     * @param string|\DateTime $confirmTime the value to be set
     * @return UserActivation
     */
    public function setConfirmTime($confirmTime);

    /**
     * Get confirmIp.
     *
     * @TODO: Map custom IP field type with inet_pton() and inet_ntop()
     * @param bool $long
     * @return string|int
     */
    public function getConfirmIp($long = false);

    /**
     * Set confirmIp.
     *
     * @TODO: Map custom IP field type with inet_pton() and inet_ntop()
     * @param string $confirmIp the value to be set
     * @return UserActivation
     */
    public function setConfirmIp($confirmIp);

    /**
     * Get active.
     *
     * @return bool active
     */
    public function getActive();

    /**
     * Set active.
     *
     * @param bool $active the value to be set
     * @return UserActivation
     */
    public function setActive($active);

    /**
     * Convert the model to an array
     *
     * @return array
     */
    public function toArray();

    /**
     * Convert an array into a model instance
     *
     * @param array $array
     * @static
     * @return User
     */
    public static function fromArray($array);
}
