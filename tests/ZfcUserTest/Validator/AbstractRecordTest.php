<?php

namespace ZfcUserTest\Validator;

use ZfcUserTest\Validator\TestAsset\AbstractRecordExtension;

class AbstractRecordTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ZfcUser\Validator\AbstractRecord::__construct
     */
    public function testConstruct()
    {
        $options = array('key'=>'value');
        new AbstractRecordExtension($options);
    }

    /**
     * @covers ZfcUser\Validator\AbstractRecord::__construct
     * @expectedException ZfcUser\Validator\Exception\InvalidArgumentException
     * @expectedExceptionMessage No key provided
     */
    public function testConstructEmptyArray()
    {
        $options = array();
        new AbstractRecordExtension($options);
    }

    /**
     * @covers ZfcUser\Validator\AbstractRecord::getMapper
     * @covers ZfcUser\Validator\AbstractRecord::setMapper
     */
    public function testGetSetMapper()
    {
        $options = array('key' => '');
        $validator = new AbstractRecordExtension($options);

        $this->assertNull($validator->getMapper());

        $mapper = $this->getMock('ZfcUser\Mapper\UserInterface');
        $validator->setMapper($mapper);
        $this->assertSame($mapper, $validator->getMapper());
    }

    /**
     * @covers ZfcUser\Validator\AbstractRecord::getKey
     * @covers ZfcUser\Validator\AbstractRecord::setKey
     */
    public function testGetSetKey()
    {
        $options = array('key' => 'username');
        $validator = new AbstractRecordExtension($options);

        $this->assertEquals('username', $validator->getKey());

        $validator->setKey('email');
        $this->assertEquals('email', $validator->getKey());
    }

    /**
     * @covers ZfcUser\Validator\AbstractRecord::query
     * @expectedException \Exception
     * @expectedExceptionMessage Invalid key used in ZfcUser validator
     */
    public function testQueryWithInvalidKey()
    {
        $options = array('key' => 'zfcUser');
        $validator = new AbstractRecordExtension($options);

        $method = new \ReflectionMethod('ZfcUserTest\Validator\TestAsset\AbstractRecordExtension', 'query');
        $method->setAccessible(true);

        $method->invoke($validator, array('test'));
    }

    /**
     * @covers ZfcUser\Validator\AbstractRecord::query
     */
    public function testQueryWithKeyUsername()
    {
        $options = array('key' => 'username');
        $validator = new AbstractRecordExtension($options);

        $mapper = $this->getMock('ZfcUser\Mapper\UserInterface');
        $mapper->expects($this->once())
               ->method('findByUsername')
               ->with('test')
               ->will($this->returnValue('ZfcUser'));

        $validator->setMapper($mapper);

        $method = new \ReflectionMethod('ZfcUserTest\Validator\TestAsset\AbstractRecordExtension', 'query');
        $method->setAccessible(true);

        $result = $method->invoke($validator, 'test');

        $this->assertEquals('ZfcUser', $result);
    }

    /**
     * @covers ZfcUser\Validator\AbstractRecord::query
     */
    public function testQueryWithKeyEmail()
    {
        $options = array('key' => 'email');
        $validator = new AbstractRecordExtension($options);

        $mapper = $this->getMock('ZfcUser\Mapper\UserInterface');
        $mapper->expects($this->once())
            ->method('findByEmail')
            ->with('test@test.com')
            ->will($this->returnValue('ZfcUser'));

        $validator->setMapper($mapper);

        $method = new \ReflectionMethod('ZfcUserTest\Validator\TestAsset\AbstractRecordExtension', 'query');
        $method->setAccessible(true);

        $result = $method->invoke($validator, 'test@test.com');

        $this->assertEquals('ZfcUser', $result);
    }
}
