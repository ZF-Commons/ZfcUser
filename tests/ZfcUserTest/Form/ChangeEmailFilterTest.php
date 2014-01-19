<?php

namespace ZfcUserTest\Form;

use ZfcUser\Form\ChangeEmailFilter as Filter;

class ChangeEmailFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ZfcUser\Form\ChangeEmailFilter::__construct
     */
    public function testConstruct()
    {
        $options = $this->getMock('ZfcUser\Options\ModuleOptions');
        $options->expects($this->once())
                ->method('getAuthIdentityFields')
                ->will($this->returnValue(array()));

        $validator = $this->getMockBuilder('ZfcUser\Validator\NoRecordExists')->disableOriginalConstructor()->getMock();
        $filter = new Filter($options, $validator);

        $inputs = $filter->getInputs();
        $this->assertArrayHasKey('identity', $inputs);
        $this->assertArrayHasKey('newIdentity', $inputs);
        $this->assertArrayHasKey('newIdentityVerify', $inputs);
    }

    /**
     * @covers ZfcUser\Form\ChangeEmailFilter::getEmailValidator
     * @covers ZfcUser\Form\ChangeEmailFilter::setEmailValidator
     */
    public function testSetGetEmailValidator()
    {
        $options = $this->getMock('ZfcUser\Options\ModuleOptions');
        $validatorInit = $this->getMockBuilder('ZfcUser\Validator\NoRecordExists')->disableOriginalConstructor()->getMock();
        $validatorNew = $this->getMockBuilder('ZfcUser\Validator\NoRecordExists')->disableOriginalConstructor()->getMock();

        $filter = new Filter($options, $validatorInit);

        $this->assertSame($validatorInit, $filter->getEmailValidator());
        $filter->setEmailValidator($validatorNew);
        $this->assertSame($validatorNew, $filter->getEmailValidator());
    }
}
