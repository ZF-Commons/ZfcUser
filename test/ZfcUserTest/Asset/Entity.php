<?php

namespace ZfcUserTest\Asset;

class Entity
{
    private $foo;

    private $password;

    /**
     * @param string $foo
     * @return Entity
     */
    public function setFoo($foo)
    {
        $this->foo = $foo;
        return $this;
    }

    /**
     * @return string
     */
    public function getFoo()
    {
        return $this->foo;
    }

    /**
     * @param string $password
     * @return Entity
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