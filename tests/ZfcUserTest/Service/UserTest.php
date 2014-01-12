<?php

namespace ZfcUserTest\Service;

use ZfcUser\Service\User as Service;

class UserTest extends \PHPUnit_Framework_TestCase
{
    protected $service;

    protected $options;

    protected $serviceManager;

    protected $formHydrator;

    public function setUp()
    {
        $service = new Service;
        $this->service = $service;

        $options = $this->getMock('ZfcUser\Options\ModuleOptions');
        $this->options = $options;

        $serviceManager = $this->getMock('Zend\ServiceManager\ServiceManager');
        $this->serviceManager = $serviceManager;

        $formHydrator = $this->getMock('Zend\Stdlib\Hydrator\HydratorInterface');
        $this->formHydrator = $formHydrator;

        $service->setOptions($options);
        $service->setServiceManager($serviceManager);
        $service->setFormHydrator($formHydrator);
    }

    public function testRegisterWithInvalidForm()
    {
        $expectArray = array('username' => 'ZfcUser');

        $this->options->expects($this->once())
                      ->method('getUserEntityClass')
                      ->will($this->returnValue('ZfcUser\Entity\User'));

        $registerForm = $this->getMockBuilder('ZfcUser\Form\Register')->disableOriginalConstructor()->getMock();
        $registerForm->expects($this->once())
                     ->method('setHydrator');
        $registerForm->expects($this->once())
                     ->method('bind');
        $registerForm->expects($this->once())
                     ->method('setData')
                     ->with($expectArray);
        $registerForm->expects($this->once())
                     ->method('isValid')
                     ->will($this->returnValue(false));

        $this->service->setRegisterForm($registerForm);

        $result = $this->service->register($expectArray);

        $this->assertFalse($result);
    }

    public function testChangePassword()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testChangeEmail()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testGetUserMapper()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testSetUserMapper()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testGetAuthService()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testSetAuthService()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testGetRegisterForm()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testSetRegisterForm()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testGetChangePasswordForm()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testSetChangePasswordForm()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testGetOptions()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testSetOptions()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testGetServiceManager()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testSetServiceManager()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testGetFormHydrator()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testSetFormHydrator()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
