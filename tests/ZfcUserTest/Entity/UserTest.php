<?php

namespace ZfcUserTest\Entity;

use ZfcUser\Entity\User as Entity;

class UserTest extends \PHPUnit_Framework_TestCase
{
    protected $user;

    public function setUp()
    {
        $user = new Entity;
        $this->user = $user;
    }

    /**
     * @covers ZfcUser\Entity\User::setId
     * @covers ZfcUser\Entity\User::getId
     */
    public function testSetGetId()
    {
        $this->user->setId(1);
        $this->assertEquals(1, $this->user->getId());
    }

    /**
     * @covers ZfcUser\Entity\User::setUsername
     * @covers ZfcUser\Entity\User::getUsername
     */
    public function testSetGetUsername()
    {
        $this->user->setUsername('zfcUser');
        $this->assertEquals('zfcUser', $this->user->getUsername());
    }

    /**
     * @covers ZfcUser\Entity\User::setDisplayName
     * @covers ZfcUser\Entity\User::getDisplayName
     */
    public function testSetGetDisplayName()
    {
        $this->user->setDisplayName('Zfc User');
        $this->assertEquals('Zfc User', $this->user->getDisplayName());
    }

    /**
     * @covers ZfcUser\Entity\User::setEmail
     * @covers ZfcUser\Entity\User::getEmail
     */
    public function testSetGetEmail()
    {
        $this->user->setEmail('zfcUser@zfcUser.com');
        $this->assertEquals('zfcUser@zfcUser.com', $this->user->getEmail());
    }

    /**
     * @covers ZfcUser\Entity\User::setPassword
     * @covers ZfcUser\Entity\User::getPassword
     */
    public function testSetGetPassword()
    {
        $this->user->setPassword('zfcUser');
        $this->assertEquals('zfcUser', $this->user->getPassword());
    }

    /**
     * @covers ZfcUser\Entity\User::setState
     * @covers ZfcUser\Entity\User::getState
     */
    public function testSetGetState()
    {
        $this->user->setState(1);
        $this->assertEquals(1, $this->user->getState());
    }
}
