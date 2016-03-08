<?php

namespace ZfcUserTest\Authentication\Storage;

use ZfcUser\Authentication\Storage\Db;

class DbTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The object to be tested.
     *
     * @var Db
     */
    protected $db;

    /**
     * Mock of Storage.
     *
     * @var storage
     */
    protected $storage;

    /**
     * Mock of Mapper.
     *
     * @var mapper
     */
    protected $mapper;

    public function setUp()
    {
        $this->mapper = $this->getMock('ZfcUser\Mapper\User');

        $db = new Db($this->mapper);
        $this->db = $db;

        $this->storage = $this->getMock('Zend\Authentication\Storage\Session');
    }

    /**
     * @covers ZfcUser\Authentication\Storage\Db::isEmpty
     */
    public function testIsEmpty()
    {
        $this->storage->expects($this->once())
                      ->method('isEmpty')
                      ->will($this->returnValue(true));

        $this->db->setStorage($this->storage);

        $this->assertTrue($this->db->isEmpty());
    }

    /**
     * @covers ZfcUser\Authentication\Storage\Db::read
     */
    public function testReadWithResolvedEntitySet()
    {
        $reflectionClass = new \ReflectionClass('ZfcUser\Authentication\Storage\Db');
        $reflectionProperty = $reflectionClass->getProperty('resolvedIdentity');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->db, 'zfcUser');

        $this->assertSame('zfcUser', $this->db->read());
    }

    /**
     * @covers ZfcUser\Authentication\Storage\Db::read
     */
    public function testReadWithoutResolvedEntitySetIdentityIntUserFound()
    {
        $this->storage->expects($this->once())
                      ->method('read')
                      ->will($this->returnValue(1));

        $this->db->setStorage($this->storage);

        $user = $this->getMock('ZfcUser\Entity\User');
        $user->setUsername('zfcUser');

        $this->mapper->expects($this->once())
                     ->method('findById')
                     ->with(1)
                     ->will($this->returnValue($user));

        $result = $this->db->read();

        $this->assertSame($user, $result);
    }

    /**
     * @covers ZfcUser\Authentication\Storage\Db::read
     */
    public function testReadWithoutResolvedEntitySetIdentityIntUserNotFound()
    {
        $this->storage->expects($this->once())
                      ->method('read')
                      ->will($this->returnValue(1));

        $this->db->setStorage($this->storage);

        $this->mapper->expects($this->once())
                     ->method('findById')
                     ->with(1)
                     ->will($this->returnValue(false));

        $result = $this->db->read();

        $this->assertNull($result);
    }

    /**
     * @covers ZfcUser\Authentication\Storage\Db::read
     */
    public function testReadWithoutResolvedEntitySetIdentityObject()
    {
        $user = $this->getMock('ZfcUser\Entity\User');
        $user->setUsername('zfcUser');

        $this->storage->expects($this->once())
                      ->method('read')
                      ->will($this->returnValue($user));

        $this->db->setStorage($this->storage);

        $result = $this->db->read();

        $this->assertSame($user, $result);
    }

    /**
     * @covers ZfcUser\Authentication\Storage\Db::write
     */
    public function testWrite()
    {
        $reflectionClass = new \ReflectionClass('ZfcUser\Authentication\Storage\Db');
        $reflectionProperty = $reflectionClass->getProperty('resolvedIdentity');
        $reflectionProperty->setAccessible(true);

        $this->storage->expects($this->once())
                      ->method('write')
                      ->with('zfcUser');

        $this->db->setStorage($this->storage);

        $this->db->write('zfcUser');

        $this->assertNull($reflectionProperty->getValue($this->db));
    }

    /**
     * @covers ZfcUser\Authentication\Storage\Db::clear
     */
    public function testClear()
    {
        $reflectionClass = new \ReflectionClass('ZfcUser\Authentication\Storage\Db');
        $reflectionProperty = $reflectionClass->getProperty('resolvedIdentity');
        $reflectionProperty->setAccessible(true);

        $this->storage->expects($this->once())
            ->method('clear');

        $this->db->setStorage($this->storage);

        $this->db->clear();

        $this->assertNull($reflectionProperty->getValue($this->db));
    }

    /**
     * @covers ZfcUser\Authentication\Storage\Db::getMapper
     */
    public function testGetMapperWithNoMapperSet()
    {
        $this->assertInstanceOf('ZfcUser\Mapper\UserInterface', $this->db->getMapper());
    }

    /**
     * @covers ZfcUser\Authentication\Storage\Db::getStorage
     * @covers ZfcUser\Authentication\Storage\Db::setStorage
     */
    public function testGetStorageWithoutStorageSet()
    {
        $this->assertInstanceOf('Zend\Authentication\Storage\Session', $this->db->getStorage());
    }

    /**
     * @covers ZfcUser\Authentication\Storage\Db::getStorage
     * @covers ZfcUser\Authentication\Storage\Db::setStorage
     */
    public function testSetGetStorage()
    {
        $storage = new \Zend\Authentication\Storage\Session('ZfcUserStorage');
        $this->db->setStorage($storage);

        $this->assertInstanceOf('Zend\Authentication\Storage\Session', $this->db->getStorage());
    }
}
