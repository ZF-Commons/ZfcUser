<?php

namespace ZfcUserTest\Service;

use ArrayObject;
use Zend\Stdlib\Hydrator\ClassMethods;
use ZfcUser\Form\RegisterForm;
use ZfcUser\Options\ModuleOptions;
use ZfcUser\Service\RegisterService;
use ZfcUserTest\Asset\User;

class RegisterServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RegisterForm
     */
    protected $form;

    /**
     * @var ModuleOptions
     */
    protected $options;

    /**
     * @var RegisterService
     */
    protected $service;

    public function setUp()
    {
        $this->options = new ModuleOptions();
        $this->options->setEntityClass('ZfcUserTest\Asset\User');

        $this->service = new RegisterService($this->options);
        $this->service->getRegisterForm()->setHydrator(new ClassMethods());
    }

    /**
     * @covers \ZfcUser\Service\RegisterService::__construct
     * @covers \ZfcUser\Service\RegisterService::register
     */
    public function testInvalidRegister()
    {
        $result = $this->service->register(array('invalid' => 'stuff'));
        $this->assertNull($result);
    }

    /**
     * @covers \ZfcUser\Service\RegisterService::register
     */
    public function testValidRegister()
    {
        $user = new User();
        $form = $this->getMock('ZfcUser\Form\RegisterForm');
        $form->expects($this->once())
             ->method('isValid')
             ->will($this->returnValue(true));

        $form->expects($this->once())
             ->method('getData')
             ->will($this->returnValue($user));

        $this->service->setRegisterForm($form);
        $result = $this->service->register(array('data' => 'data'));

        $this->assertEquals($user, $result);
    }

    /**
     * @covers \ZfcUser\Service\RegisterService::register
     */
    public function testInvalidUserRegister()
    {
        $this->setExpectedException('ZfcUser\Service\Exception\InvalidUserException');

        $form = $this->getMock('ZfcUser\Form\RegisterForm');
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue(new ArrayObject()));

        $this->service->setRegisterForm($form);
        $this->service->register(array('data' => 'data'));
    }

    /**
     * @covers \ZfcUser\Service\RegisterService::setRegisterForm
     * @covers \ZfcUser\Service\RegisterService::getRegisterForm
     */
    public function testGetSetRegisterForm()
    {
        $this->assertInstanceOf('ZfcUser\Form\RegisterForm', $this->service->getRegisterForm());

        $form = new RegisterForm();
        $this->service->setRegisterForm($form);
        $this->assertEquals($form, $this->service->getRegisterForm());
    }

    /**
     * @covers \ZfcUser\Service\RegisterService::getUserPrototype
     */
    public function testUserPrototype()
    {
        $user = $this->service->getUserPrototype();
        $this->assertInstanceOf('ZfcUserTest\Asset\User', $user);
    }

    /**
     * @covers \ZfcUser\Service\RegisterService::getUserPrototype
     */
    public function testMissingUserPrototype()
    {
        $this->setExpectedException('ZfcUser\Service\Exception\InvalidUserException');
        $this->options->setEntityClass('DoesNotExist');
        $this->service->getUserPrototype();
    }

    /**
     * @covers \ZfcUser\Service\RegisterService::getUserPrototype
     */
    public function testInvalidUserPrototype()
    {
        $this->setExpectedException('ZfcUser\Service\Exception\InvalidUserException');
        $this->options->setEntityClass('ArrayObject');
        $this->service->getUserPrototype();
    }
}
