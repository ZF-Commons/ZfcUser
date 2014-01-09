<?php

namespace ZfcUserTest\Authentication\Adapter;

use ZfcUser\Authentication\Adapter\Db;

class DbTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The object to be tested.
     *
     * @var Db
     */
    protected $db;

    protected function setUp()
    {
        $this->db = new Db;
    }

    /**
     * @covers ZfcUser\Authentication\Adapter\Db::logout
     */
    public function testLogout()
    {
        $storage = $this->getMock('Zend\Authentication\Storage\Session');
        $storage->expects($this->any())
                ->method('getNameSpace')
                ->will($this->returnValue('test'));

        $authEvent = $this->getMock('ZfcUser\Authentication\Adapter\AdapterChainEvent');

        $this->db->setStorage($storage);
        /*
         * @Todo: Need to start the session for the test to pass...
         * $this->db->logout($authEvent);
         */
    }

    /**
     * @covers ZfcUser\Authentication\Adapter\Db::updateUserPasswordHash
     */
    public function testUpdateUserPasswordHashWithSameCost()
    {
        $user = $this->getMock('ZfcUser\Entity\User');
        $user->expects($this->once())
             ->method('getPassword')
             ->will($this->returnValue('$2a$10$x05G2P803MrB3jaORBXBn.QHtiYzGQOBjQ7unpEIge.Mrz6c3KiVm'));

        $bcrypt = $this->getMock('Zend\Crypt\Password\Bcrypt');
        $bcrypt->expects($this->once())
               ->method('getCost')
               ->will($this->returnValue('10'));

        $method = new \ReflectionMethod(
            'ZfcUser\Authentication\Adapter\Db', 'updateUserPasswordHash'
        );
        $method->setAccessible(true);

        $result = $method->invoke($this->db, $user, 'ZfcUser', $bcrypt);
        $this->assertNull($result);
    }

    /**
     * @covers ZfcUser\Authentication\Adapter\Db::updateUserPasswordHash
     */
    public function testUpdateUserPasswordHashWithoutSameCost()
    {
        $user = $this->getMock('ZfcUser\Entity\User');
        $user->expects($this->once())
             ->method('getPassword')
             ->will($this->returnValue('$2a$10$x05G2P803MrB3jaORBXBn.QHtiYzGQOBjQ7unpEIge.Mrz6c3KiVm'));
        $user->expects($this->once())
             ->method('setPassword')
             ->with('$2a$10$D41KPuDCn6iGoESjnLee/uE/2Xo985sotVySo2HKDz6gAO4hO/Gh6');

        $bcrypt = $this->getMock('Zend\Crypt\Password\Bcrypt');
        $bcrypt->expects($this->once())
            ->method('getCost')
            ->will($this->returnValue('5'));
        $bcrypt->expects($this->once())
               ->method('create')
               ->with('ZfcUserNew')
               ->will($this->returnValue('$2a$10$D41KPuDCn6iGoESjnLee/uE/2Xo985sotVySo2HKDz6gAO4hO/Gh6'));

        $mapper = $this->getMock('ZfcUser\Mapper\User');
        $mapper->expects($this->once())
               ->method('update')
               ->with($user);

        $this->db->setMapper($mapper);

        $method = new \ReflectionMethod(
            'ZfcUser\Authentication\Adapter\Db', 'updateUserPasswordHash'
        );
        $method->setAccessible(true);

        $result = $method->invoke($this->db, $user, 'ZfcUserNew', $bcrypt);
        $this->assertInstanceOf('ZfcUser\Authentication\Adapter\Db', $result);
    }
}