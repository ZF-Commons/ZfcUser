<?php

namespace ZfcUser\Model;

use DateTime,
    ZfcBase\Model\ModelAbstract;

class UserActivationBase extends ModelAbstract implements UserActivation
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $userId;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var \DateTime
     */
    protected $requestTime;

    /**
     * @var \DateTime
     */
    protected $validTo;

    /**
     * @var int
     */
    protected $requestIp;

    /**
     * @var \DateTime
     */
    protected $confirmTime;

    /**
     * @var int
     */
    protected $confirmIp;

    /**
     * @var bool
     */
    protected $active;

    /**
     * Get id.
     *
     * @return int id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id.
     *
     * @param int $id the value to be set
     * @return UserActivation
     */
    public function setId($id)
    {
        $this->id = (int) $id;
        return $this;
    }

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
     * @return UserActivation
     */
    public function setUserId($userId)
    {
        $this->userId = (int) $userId;
        return $this;
    }

    /**
     * Get token.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set token.
     *
     * @param string $token the value to be set
     * @return UserActivation
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Get requestTime.
     *
     * @return \DateTime registerTime
     */
    public function getRequestTime()
    {
        return $this->requestTime;
    }

    /**
     * Set requestTime.
     *
     * @param string|\DateTime $requestTime the value to be set
     * @return UserActivation
     */
    public function setRequestTime($requestTime)
    {
        if ($requestTime instanceof \DateTime) {
            $this->requestTime = $requestTime;
        } else {
            $this->requestTime = new \DateTime($requestTime);
        }
        return $this;
    }

    /**
     * Get validTo.
     *
     * @return \DateTime validTo
     */
    public function getValidTo()
    {
        return $this->validTo;
    }

    /**
     * Set validTo.
     *
     * @param string|\DateTime $validTo the value to be set
     * @return UserActivation
     */
    public function setValidTo($validTo)
    {
        if ($validTo instanceof \DateTime) {
            $this->validTo = $validTo;
        } else {
            $this->validTo = new \DateTime($validTo);
        }
        return $this;
    }

    /**
     * Get requestIp.
     *
     * @TODO: Map custom IP field type with inet_pton() and inet_ntop()
     * @param bool $long
     * @return string|int
     */
    public function getRequestIp($long = false)
    {
        if (true === $long) {
            return $this->requestIp;
        }
        return long2ip($this->requestIp);
    }

    /**
     * Set requestIp.
     *
     * @TODO: Map custom IP field type with inet_pton() and inet_ntop()
     * @param string $requestIp the value to be set
     * @return UserActivation
     */
    public function setRequestIp($requestIp)
    {
        $this->requestIp = ip2long($requestIp);
        return $this;
    }

    /**
     * Get confirmTime.
     *
     * @return \DateTime registerTime
     */
    public function getConfirmTime()
    {
        return $this->confirmTime;
    }

    /**
     * Set confirmTime.
     *
     * @param string|\DateTime $confirmTime the value to be set
     * @return UserActivation
     */
    public function setConfirmTime($confirmTime)
    {
        if ($confirmTime instanceof \DateTime) {
            $this->confirmTime = $confirmTime;
        } else {
            $this->confirmTime = new \DateTime($confirmTime);
        }
        return $this;
    }

    /**
     * Get confirmIp.
     *
     * @TODO: Map custom IP field type with inet_pton() and inet_ntop()
     * @param bool $long
     * @return string|int
     */
    public function getConfirmIp($long = false)
    {
        if (true === $long) {
            return $this->confirmIp;
        }
        return long2ip($this->confirmIp);
    }

    /**
     * Set confirmIp.
     *
     * @TODO: Map custom IP field type with inet_pton() and inet_ntop()
     * @param string $confirmIp the value to be set
     * @return UserActivation
     */
    public function setConfirmIp($confirmIp)
    {
        $this->confirmIp = ip2long($confirmIp);
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
     * @return UserActivation
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }
}
