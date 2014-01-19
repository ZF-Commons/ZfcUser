<?php

namespace ZfcUserTest\Form;

use ZfcUser\Form\LoginFilter as Filter;

class LoginFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ZfcUser\Form\LoginFilter::__construct
     */
    public function testConstruct()
    {
        $options = $this->getMock('ZfcUser\Options\ModuleOptions');
        $options->expects($this->once())
                ->method('getAuthIdentityFields')
                ->will($this->returnValue(array()));

        $filter = new Filter($options);

        $inputs = $filter->getInputs();
        $this->assertArrayHasKey('identity', $inputs);
        $this->assertArrayHasKey('credential', $inputs);
    }
}
