<?php

namespace ZfcUserTest\Authentication\Storage;

use ZfcUser\Authentication\Storage\Mapper;

class MapperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The object to be tested.
     *
     * @var Db
     */
    protected $adapter;

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

    /**
     * @covers ZfcUser\Authentication\Storage\Mapper::__construct
     */
    public function setUp()
    {
        $this->storage = $this->getMock('Zend\Authentication\Storage\StorageInterface');
        $this->mapper = $this->getMock('ZfcUser\Mapper\UserInterface');
        
        $this->adapter = new Mapper($this->mapper, $this->storage);
    }

    /**
     * @covers ZfcUser\Authentication\Storage\Mapper::isEmpty
     */
    public function testIsEmpty()
    {
        $this->storage->expects($this->once())
                      ->method('isEmpty')
                      ->will($this->returnValue(true));

        $this->assertTrue($this->adapter->isEmpty());
    }

    /**
     * @covers ZfcUser\Authentication\Storage\Mapper::isEmpty
     */
    public function testIsEmptyWhenNotEmpty()
    {
        $this->adapter->write('identity');
        
        $this->storage->expects($this->once())
                      ->method('isEmpty')
                      ->will($this->returnValue(false));
        
        $this->storage->expects($this->once())
                      ->method('read')
                      ->will($this->returnValue('identity'));
        
        $this->mapper->expects($this->once())
                      ->method('findById')
                      ->will($this->returnValue($this->getMock('ZfcUser\Entity\UserInterface')));

        $this->assertFalse($this->adapter->isEmpty());
    }
    
    /**
     * @covers ZfcUser\Authentication\Storage\Mapper::isEmpty
     */
    public function testIsEmptyWhenStorageIsEmptyButAdapterHasNoIdentity()
    {
        $this->adapter->write('identity');
        
        $this->storage->expects($this->once())
                      ->method('isEmpty')
                      ->will($this->returnValue(false));
        
        $this->storage->expects($this->once())
                      ->method('read')
                      ->will($this->returnValue('identity'));
        
        $this->mapper->expects($this->once())
                      ->method('findById')
                      ->will($this->returnValue(null));

        $this->assertTrue($this->adapter->isEmpty());
    }
    
    /**
     * @covers ZfcUser\Authentication\Storage\Mapper::read
     */
    public function testReadWithResolvedEntitySet()
    {
        $reflectionClass = new \ReflectionClass('ZfcUser\Authentication\Storage\Mapper');
        $reflectionProperty = $reflectionClass->getProperty('resolvedIdentity');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->adapter, 'zfcUser');

        $this->assertSame('zfcUser', $this->adapter->read());
    }

    /**
     * @covers ZfcUser\Authentication\Storage\Mapper::read
     */
    public function testReadWithoutResolvedEntitySetIdentityIntUserFound()
    {
        $this->storage->expects($this->once())
                      ->method('read')
                      ->will($this->returnValue(1));

        $user = $this->getMock('ZfcUser\Entity\User');
        $user->setUsername('zfcUser');

        $this->mapper->expects($this->once())
                     ->method('findById')
                     ->with(1)
                     ->will($this->returnValue($user));

        $result = $this->adapter->read();

        $this->assertSame($user, $result);
    }

    /**
     * @covers ZfcUser\Authentication\Storage\Mapper::read
     */
    public function testReadWithoutResolvedEntitySetIdentityIntUserNotFound()
    {
        $this->storage->expects($this->once())
                      ->method('read')
                      ->will($this->returnValue(1));

        $this->mapper->expects($this->once())
                     ->method('findById')
                     ->with(1)
                     ->will($this->returnValue(false));

        $result = $this->adapter->read();

        $this->assertNull($result);
    }

    /**
     * @covers ZfcUser\Authentication\Storage\Mapper::read
     */
    public function testReadWithoutResolvedEntitySetIdentityObject()
    {
        $user = $this->getMock('ZfcUser\Entity\User');
        $user->setUsername('zfcUser');

        $this->storage->expects($this->once())
                      ->method('read')
                      ->will($this->returnValue($user));

        $result = $this->adapter->read();

        $this->assertSame($user, $result);
    }

    /**
     * @covers ZfcUser\Authentication\Storage\Mapper::write
     */
    public function testWrite()
    {
        $reflectionClass = new \ReflectionClass('ZfcUser\Authentication\Storage\Mapper');
        $reflectionProperty = $reflectionClass->getProperty('resolvedIdentity');
        $reflectionProperty->setAccessible(true);

        $this->storage->expects($this->once())
                      ->method('write')
                      ->with('zfcUser');

        $this->adapter->write('zfcUser');

        $this->assertNull($reflectionProperty->getValue($this->adapter));
    }

    /**
     * @covers ZfcUser\Authentication\Storage\Mapper::clear
     */
    public function testClear()
    {
        $reflectionClass = new \ReflectionClass('ZfcUser\Authentication\Storage\Mapper');
        $reflectionProperty = $reflectionClass->getProperty('resolvedIdentity');
        $reflectionProperty->setAccessible(true);

        $this->storage->expects($this->once())
            ->method('clear');

        $this->adapter->clear();

        $this->assertNull($reflectionProperty->getValue($this->adapter));
    }

}
