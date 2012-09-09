<?php

namespace ZfcUser\Entity;

class RememberMe
{
    protected $sid;

    protected $token;

    protected $email;

    public function getEmail()
    {
        return $this->email;
    }

    public function getSid()
    {
        return $this->sid;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setSid($sid)
    {
        $this->sid = $sid;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }
}