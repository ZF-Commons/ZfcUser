<?php

namespace ZfcUserTest\Mapper;

use ZfcUser\Mapper\UserHydrator as Hydrator;

class UserHydratorTest extends \PHPUnit_Framework_TestCase
{
    protected $hydrator;

    public function setUp()
    {
        $hydrator = new Hydrator;
        $this->hydrator = $hydrator;
    }

    /**
     * @covers ZfcUser\Mapper\User::extract
     * @expectedException ZfcUser\Mapper\Exception\InvalidArgumentException
     */
    public function testExtractWithInvalidUserObject()
    {
        $user = new \StdClass;
        $this->hydrator->extract($user);
    }

    /**
     * @covers ZfcUser\Mapper\User::extract
     * @covers ZfcUser\Mapper\User::mapField
     */
    public function testExtractWithValidUserObject()
    {
        $user = new \ZfcUser\Entity\User;

        $expectArray = array(
            'username' => 'zfcuser',
            'email' => 'Zfc User',
            'display_name' => 'ZfcUser',
            'password' => 'ZfcUserPassword',
            'state' => 1,
            'user_id' => 1
        );

        $user->setUsername($expectArray['username']);
        $user->setDisplayName($expectArray['display_name']);
        $user->setEmail($expectArray['email']);
        $user->setPassword($expectArray['password']);
        $user->setState($expectArray['state']);
        $user->setId($expectArray['user_id']);

        $result = $this->hydrator->extract($user);

        $this->assertEquals($expectArray, $result);
    }

    /**
     * @covers ZfcUser\Mapper\User::hydrate
     * @expectedException ZfcUser\Mapper\Exception\InvalidArgumentException
     */
    public function testHydrateWithInvalidUserObject()
    {
        $user = new \StdClass;
        $this->hydrator->hydrate(array(), $user);
    }

    /**
     * @covers ZfcUser\Mapper\User::hydate
     * @covers ZfcUser\Mapper\User::mapField
     */
    public function testHydrateWithValidUserObject()
    {
        $user = new \ZfcUser\Entity\User;

        $expectArray = array(
            'username' => 'zfcuser',
            'email' => 'Zfc User',
            'display_name' => 'ZfcUser',
            'password' => 'ZfcUserPassword',
            'state' => 1,
            'user_id' => 1
        );

        $result = $this->hydrator->hydrate($expectArray, $user);

        $this->assertEquals($expectArray['username'], $result->getUsername());
        $this->assertEquals($expectArray['email'], $result->getEmail());
        $this->assertEquals($expectArray['display_name'], $result->getDisplayName());
        $this->assertEquals($expectArray['password'], $result->getPassword());
        $this->assertEquals($expectArray['state'], $result->getState());
        $this->assertEquals($expectArray['user_id'], $result->getId());
    }
}