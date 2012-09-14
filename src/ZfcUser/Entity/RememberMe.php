<?php

namespace ZfcUser\Entity;

class RememberMe
{
    protected $sid;

    protected $token;

    protected $user_id;

    public function getSid()
    {
        return $this->sid;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setSid($sid)
    {
        $this->sid = $sid;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }
}