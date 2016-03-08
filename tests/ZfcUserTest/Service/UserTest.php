<?php

namespace ZfcUserTest\Service;

use Zend\Authentication\AuthenticationService;
use Zend\EventManager\EventManager;
use Zend\Hydrator\HydratorInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcUser\Form\Register;
use ZfcUser\Mapper\UserInterface;
use ZfcUser\Options\ModuleOptions;
use ZfcUser\Service\User as Service;
use Zend\Crypt\Password\Bcrypt;
use ZfcUser\Service\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
    /** @var User */
    protected $service;

    /** @var  ModuleOptions */
    protected $options;

    /** @var ServiceManager */
    protected $serviceManager;

    /** @var HydratorInterface */
    protected $formHydrator;

    /** @var EventManager */
    protected $eventManager;

    /** @var UserInterface */
    protected $mapper;

    /** @var AuthenticationService */
    protected $authService;

    /** @var Register */
    protected $registerForm;

    public function setUp()
    {
        $this->mapper = $this->getMock('ZfcUser\Mapper\UserInterface');

        $this->authService = $this->getMockBuilder('Zend\Authentication\AuthenticationService')
            ->disableOriginalConstructor()
            ->getMock();

        $this->registerForm = $this->getMockBuilder('ZfcUser\Form\Register')
            ->disableOriginalConstructor()
            ->getMock();

        $this->options = $this->getMock('ZfcUser\Options\ModuleOptions');

        $this->serviceManager = $this->getMock('Zend\ServiceManager\ServiceManager');

        $this->eventManager = $this->getMock('Zend\EventManager\EventManager');

        $this->formHydrator = $this->getMock('Zend\Stdlib\Hydrator\HydratorInterface');

        $service = new Service($this->mapper, $this->authService, $this->registerForm, $this->options, $this->formHydrator);
        $this->service = $service;

        $service->setEventManager($this->eventManager);
    }

    /**
     * @covers ZfcUser\Service\User::register
     */
    public function testRegisterWithInvalidForm()
    {
        $expectArray = array('username' => 'ZfcUser');

        $this->options->expects($this->once())
            ->method('getUserEntityClass')
            ->will($this->returnValue('ZfcUser\Entity\User'));

        $registerForm = $this->registerForm;
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

        $result = $this->service->register($expectArray);

        $this->assertFalse($result);
    }

    /**
     * @covers ZfcUser\Service\User::register
     */
    public function testRegisterWithUsernameAndDisplayNameUserStateDisabled()
    {
        $expectArray = array('username' => 'ZfcUser', 'display_name' => 'Zfc User');

        $user = $this->getMock('ZfcUser\Entity\User');
        $user->expects($this->once())
            ->method('setPassword');
        $user->expects($this->once())
            ->method('getPassword');
        $user->expects($this->once())
            ->method('setUsername')
            ->with('ZfcUser');
        $user->expects($this->once())
            ->method('setDisplayName')
            ->with('Zfc User');
        $user->expects($this->once())
            ->method('setState')
            ->with(1);

        $this->options->expects($this->once())
            ->method('getUserEntityClass')
            ->will($this->returnValue('ZfcUser\Entity\User'));
        $this->options->expects($this->once())
            ->method('getPasswordCost')
            ->will($this->returnValue(4));
        $this->options->expects($this->once())
            ->method('getEnableUsername')
            ->will($this->returnValue(true));
        $this->options->expects($this->once())
            ->method('getEnableDisplayName')
            ->will($this->returnValue(true));
        $this->options->expects($this->once())
            ->method('getEnableUserState')
            ->will($this->returnValue(true));
        $this->options->expects($this->once())
            ->method('getDefaultUserState')
            ->will($this->returnValue(1));

        $registerForm = $this->registerForm;
        $registerForm->expects($this->once())
            ->method('setHydrator');
        $registerForm->expects($this->once())
            ->method('bind');
        $registerForm->expects($this->once())
            ->method('setData')
            ->with($expectArray);
        $registerForm->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($user));
        $registerForm->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $this->eventManager->expects($this->exactly(2))
            ->method('trigger');

        $this->mapper->expects($this->once())
            ->method('insert')
            ->with($user)
            ->will($this->returnValue($user));

        $result = $this->service->register($expectArray);

        $this->assertSame($user, $result);
    }

    /**
     * @covers ZfcUser\Service\User::register
     */
    public function testRegisterWithDefaultUserStateOfZero()
    {
        $expectArray = array('username' => 'ZfcUser', 'display_name' => 'Zfc User');

        $user = $this->getMock('ZfcUser\Entity\User');
        $user->expects($this->once())
            ->method('setPassword');
        $user->expects($this->once())
            ->method('getPassword');
        $user->expects($this->once())
            ->method('setUsername')
            ->with('ZfcUser');
        $user->expects($this->once())
            ->method('setDisplayName')
            ->with('Zfc User');
        $user->expects($this->once())
            ->method('setState')
            ->with(0);

        $this->options->expects($this->once())
            ->method('getUserEntityClass')
            ->will($this->returnValue('ZfcUser\Entity\User'));
        $this->options->expects($this->once())
            ->method('getPasswordCost')
            ->will($this->returnValue(4));
        $this->options->expects($this->once())
            ->method('getEnableUsername')
            ->will($this->returnValue(true));
        $this->options->expects($this->once())
            ->method('getEnableDisplayName')
            ->will($this->returnValue(true));
        $this->options->expects($this->once())
            ->method('getEnableUserState')
            ->will($this->returnValue(true));
        $this->options->expects($this->once())
            ->method('getDefaultUserState')
            ->will($this->returnValue(0));

        $registerForm = $this->registerForm;
        $registerForm->expects($this->once())
            ->method('setHydrator');
        $registerForm->expects($this->once())
            ->method('bind');
        $registerForm->expects($this->once())
            ->method('setData')
            ->with($expectArray);
        $registerForm->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($user));
        $registerForm->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $this->eventManager->expects($this->exactly(2))
            ->method('trigger');

        $this->mapper->expects($this->once())
            ->method('insert')
            ->with($user)
            ->will($this->returnValue($user));

        $result = $this->service->register($expectArray);

        $this->assertSame($user, $result);
        $this->assertEquals(0, $user->getState());
    }

    /**
     * @covers ZfcUser\Service\User::register
     */
    public function testRegisterWithUserStateDisabled()
    {
        $expectArray = array('username' => 'ZfcUser', 'display_name' => 'Zfc User');

        $user = $this->getMock('ZfcUser\Entity\User');
        $user->expects($this->once())
            ->method('setPassword');
        $user->expects($this->once())
            ->method('getPassword');
        $user->expects($this->once())
            ->method('setUsername')
            ->with('ZfcUser');
        $user->expects($this->once())
            ->method('setDisplayName')
            ->with('Zfc User');
        $user->expects($this->never())
            ->method('setState');

        $this->options->expects($this->once())
            ->method('getUserEntityClass')
            ->will($this->returnValue('ZfcUser\Entity\User'));
        $this->options->expects($this->once())
            ->method('getPasswordCost')
            ->will($this->returnValue(4));
        $this->options->expects($this->once())
            ->method('getEnableUsername')
            ->will($this->returnValue(true));
        $this->options->expects($this->once())
            ->method('getEnableDisplayName')
            ->will($this->returnValue(true));
        $this->options->expects($this->once())
            ->method('getEnableUserState')
            ->will($this->returnValue(false));
        $this->options->expects($this->never())
            ->method('getDefaultUserState');

        $registerForm = $this->registerForm;
        $registerForm->expects($this->once())
            ->method('setHydrator');
        $registerForm->expects($this->once())
            ->method('bind');
        $registerForm->expects($this->once())
            ->method('setData')
            ->with($expectArray);
        $registerForm->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($user));
        $registerForm->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $this->eventManager->expects($this->exactly(2))
            ->method('trigger');

        $this->mapper->expects($this->once())
            ->method('insert')
            ->with($user)
            ->will($this->returnValue($user));

        $result = $this->service->register($expectArray);

        $this->assertSame($user, $result);
        $this->assertEquals(0, $user->getState());
    }

    /**
     * @covers ZfcUser\Service\User::changePassword
     */
    public function testChangePasswordWithWrongOldPassword()
    {
        $data = array('newCredential' => 'zfcUser', 'credential' => 'zfcUserOld');

        $this->options->expects($this->any())
            ->method('getPasswordCost')
            ->will($this->returnValue(4));

        $bcrypt = new Bcrypt();
        $bcrypt->setCost($this->options->getPasswordCost());

        $user = $this->getMock('ZfcUser\Entity\User');
        $user->expects($this->any())
            ->method('getPassword')
            ->will($this->returnValue($bcrypt->create('wrongPassword')));

        $this->authService->expects($this->any())
            ->method('getIdentity')
            ->will($this->returnValue($user));

        $result = $this->service->changePassword($data);
        $this->assertFalse($result);
    }

    /**
     * @covers ZfcUser\Service\User::changePassword
     */
    public function testChangePassword()
    {
        $data = array('newCredential' => 'zfcUser', 'credential' => 'zfcUserOld');

        $this->options->expects($this->any())
            ->method('getPasswordCost')
            ->will($this->returnValue(4));

        $bcrypt = new Bcrypt();
        $bcrypt->setCost($this->options->getPasswordCost());

        $user = $this->getMock('ZfcUser\Entity\User');
        $user->expects($this->any())
            ->method('getPassword')
            ->will($this->returnValue($bcrypt->create($data['credential'])));
        $user->expects($this->any())
            ->method('setPassword');

        $this->authService->expects($this->any())
            ->method('getIdentity')
            ->will($this->returnValue($user));

        $this->eventManager->expects($this->exactly(2))
            ->method('trigger');

        $this->mapper->expects($this->once())
            ->method('update')
            ->with($user);

        $result = $this->service->changePassword($data);
        $this->assertTrue($result);
    }

    /**
     * @covers ZfcUser\Service\User::changeEmail
     */
    public function testChangeEmail()
    {
        $data = array('credential' => 'zfcUser', 'newIdentity' => 'zfcUser@zfcUser.com');

        $this->options->expects($this->any())
            ->method('getPasswordCost')
            ->will($this->returnValue(4));

        $bcrypt = new Bcrypt();
        $bcrypt->setCost($this->options->getPasswordCost());

        $user = $this->getMock('ZfcUser\Entity\User');
        $user->expects($this->any())
            ->method('getPassword')
            ->will($this->returnValue($bcrypt->create($data['credential'])));
        $user->expects($this->any())
            ->method('setEmail')
            ->with('zfcUser@zfcUser.com');

        $this->authService->expects($this->any())
            ->method('getIdentity')
            ->will($this->returnValue($user));

        $this->eventManager->expects($this->exactly(2))
            ->method('trigger');

        $this->mapper->expects($this->once())
            ->method('update')
            ->with($user);

        $result = $this->service->changeEmail($data);
        $this->assertTrue($result);
    }

    /**
     * @covers ZfcUser\Service\User::changeEmail
     */
    public function testChangeEmailWithWrongPassword()
    {
        $data = array('credential' => 'zfcUserOld');

        $this->options->expects($this->any())
            ->method('getPasswordCost')
            ->will($this->returnValue(4));

        $bcrypt = new Bcrypt();
        $bcrypt->setCost($this->options->getPasswordCost());

        $user = $this->getMock('ZfcUser\Entity\User');
        $user->expects($this->any())
            ->method('getPassword')
            ->will($this->returnValue($bcrypt->create('wrongPassword')));

        $this->authService->expects($this->any())
            ->method('getIdentity')
            ->will($this->returnValue($user));

        $result = $this->service->changeEmail($data);
        $this->assertFalse($result);
    }

    /**
     * @covers ZfcUser\Service\User::getUserMapper
     */
    public function testGetUserMapper()
    {
        $this->assertInstanceOf('ZfcUser\Mapper\UserInterface', $this->service->getUserMapper());
    }

    /**
     * @covers ZfcUser\Service\User::getAuthService
     */
    public function testGetAuthService()
    {
        $this->assertInstanceOf('Zend\Authentication\AuthenticationService', $this->service->getAuthService());
    }

    /**
     * @covers ZfcUser\Service\User::getRegisterForm
     */
    public function testGetRegisterForm()
    {
        $this->assertInstanceOf('ZfcUser\Form\Register', $this->service->getRegisterForm());
    }

    /**
     * @covers ZfcUser\Service\User::getOptions
     */
    public function testGetOptions()
    {
        $this->assertInstanceOf('ZfcUser\Options\ModuleOptions', $this->service->getOptions());
    }

    /**
     * @covers ZfcUser\Service\User::getFormHydrator
     */
    public function testGetFormHydrator()
    {
        $this->assertInstanceOf('Zend\Stdlib\Hydrator\HydratorInterface', $this->service->getFormHydrator());
    }
}
