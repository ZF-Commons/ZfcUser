<?php

namespace ZfcUserTest\Form;

use ZfcUser\Form\RegisterFilter as Filter;

class RegisterFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ZfcUser\Form\RegisterFilter::__construct
     */
    public function testConstruct()
    {
        $options = $this->getMock('ZfcUser\Options\ModuleOptions');
        $options->expects($this->once())
                ->method('getEnableUsername')
                ->will($this->returnValue(true));
        $options->expects($this->once())
                ->method('getEnableDisplayName')
                ->will($this->returnValue(true));

        $emailValidator = $this->getMockBuilder('ZfcUser\Validator\NoRecordExists')->disableOriginalConstructor()->getMock();
        $usernameValidator = $this->getMockBuilder('ZfcUser\Validator\NoRecordExists')->disableOriginalConstructor()->getMock();

        $filter = new Filter($emailValidator, $usernameValidator, $options);

        $inputs = $filter->getInputs();
        $this->assertArrayHasKey('username', $inputs);
        $this->assertArrayHasKey('email', $inputs);
        $this->assertArrayHasKey('display_name', $inputs);
        $this->assertArrayHasKey('password', $inputs);
        $this->assertArrayHasKey('passwordVerify', $inputs);
    }

    public function testSetGetEmailValidator()
    {
        $options = $this->getMock('ZfcUser\Options\ModuleOptions');
        $validatorInit = $this->getMockBuilder('ZfcUser\Validator\NoRecordExists')->disableOriginalConstructor()->getMock();
        $validatorNew = $this->getMockBuilder('ZfcUser\Validator\NoRecordExists')->disableOriginalConstructor()->getMock();

        $filter = new Filter($validatorInit, $validatorInit, $options);

        $this->assertSame($validatorInit, $filter->getEmailValidator());
        $filter->setEmailValidator($validatorNew);
        $this->assertSame($validatorNew, $filter->getEmailValidator());
    }

    public function testSetGetUsernameValidator()
    {
        $options = $this->getMock('ZfcUser\Options\ModuleOptions');
        $validatorInit = $this->getMockBuilder('ZfcUser\Validator\NoRecordExists')->disableOriginalConstructor()->getMock();
        $validatorNew = $this->getMockBuilder('ZfcUser\Validator\NoRecordExists')->disableOriginalConstructor()->getMock();

        $filter = new Filter($validatorInit, $validatorInit, $options);

        $this->assertSame($validatorInit, $filter->getUsernameValidator());
        $filter->setUsernameValidator($validatorNew);
        $this->assertSame($validatorNew, $filter->getUsernameValidator());
    }

    public function testSetGetOptions()
    {
        $options = $this->getMock('ZfcUser\Options\ModuleOptions');
        $optionsNew = $this->getMock('ZfcUser\Options\ModuleOptions');
        $validatorInit = $this->getMockBuilder('ZfcUser\Validator\NoRecordExists')->disableOriginalConstructor()->getMock();
        $filter = new Filter($validatorInit, $validatorInit, $options);

        $this->assertSame($options, $filter->getOptions());
        $filter->setOptions($optionsNew);
        $this->assertSame($optionsNew, $filter->getOptions());
    }
}
