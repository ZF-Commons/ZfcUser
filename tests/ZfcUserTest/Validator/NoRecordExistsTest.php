<?php

namespace ZfcUserTest\Validator;

use ZfcUser\Validator\NoRecordExists as Validator;

class NoRecordExistsTest extends \PHPUnit_Framework_TestCase
{
    protected $validator;

    protected $mapper;

    public function setUp()
    {
        $options = array('key' => 'username');
        $validator = new Validator($options);
        $this->validator = $validator;

        $mapper = $this->getMock('ZfcUser\Mapper\UserInterface');
        $this->mapper = $mapper;

        $validator->setMapper($mapper);
    }

    /**
     * @covers ZfcUser\Validator\NoRecordExists::isValid
     */
    public function testIsValid()
    {
        $this->mapper->expects($this->once())
                     ->method('findByUsername')
                     ->with('zfcUser')
                     ->will($this->returnValue(false));

        $result = $this->validator->isValid('zfcUser');
        $this->assertTrue($result);
    }

    /**
     * @covers ZfcUser\Validator\NoRecordExists::isValid
     */
    public function testIsInvalid()
    {
        $this->mapper->expects($this->once())
                     ->method('findByUsername')
                     ->with('zfcUser')
                     ->will($this->returnValue('zfcUser'));

        $result = $this->validator->isValid('zfcUser');
        $this->assertFalse($result);

        $options = $this->validator->getOptions();
        $this->assertArrayHasKey(\ZfcUser\Validator\AbstractRecord::ERROR_RECORD_FOUND, $options['messages']);
        $this->assertEquals($options['messageTemplates'][\ZfcUser\Validator\AbstractRecord::ERROR_RECORD_FOUND], $options['messages'][\ZfcUser\Validator\AbstractRecord::ERROR_RECORD_FOUND]);
    }
}
