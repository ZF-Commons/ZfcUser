<?php

namespace ZfcUserTest\Service;

use ZfcUser\Service\User as Service;
use Zend\Crypt\Password\Bcrypt;

class UserTest extends \PHPUnit_Framework_TestCase
{
    protected $service;

    protected $options;

    protected $serviceManager;

    protected $formHydrator;

    protected $eventManager;

    protected $mapper;

    protected $authService;

    protected $cryptoService;

    public function setUp()
    {
        $this->service = new Service;

        $this->options = $this->getMock('ZfcUser\Options\ModuleOptions');

        $this->serviceManager = $this->getMock('Zend\ServiceManager\ServiceManager');

        $this->eventManager = $this->getMock('Zend\EventManager\EventManager');

        $this->cryptoService = $this->getMockForAbstractClass(
            'Zend\Crypt\Password\PasswordInterface'
        );
        $this->formHydrator = $this->getMockForAbstractClass('ZfcUser\Mapper\HydratorInterface');
        $this->formHydrator
            ->expects($this->any())
            ->method('getCryptoService')
            ->will($this->returnValue($this->cryptoService));

        $this->mapper = $this->getMockForAbstractClass('ZfcUser\Mapper\UserInterface');

        $this->authService = $this->getMockForAbstractClass(
            'Zend\Authentication\AuthenticationServiceInterface'
        );

        $this->service->setOptions($this->options);
        $this->service->setServiceManager($this->serviceManager);
        $this->service->setFormHydrator($this->formHydrator);
        $this->service->setEventManager($this->eventManager);
        $this->service->setUserMapper($this->mapper);
        $this->service->setAuthService($this->authService);
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

    /**
     * @covers ZfcUser\Service\User::register
     */
    public function testRegisterWithUsernameAndDisplayNameUserStateDisabled()
    {
        $expectArray = array('username' => 'ZfcUser', 'display_name' => 'Zfc User');

        $user = $this->getMock('ZfcUser\Entity\User');

        $this->options->expects($this->once())
                      ->method('getUserEntityClass')
                      ->will($this->returnValue('ZfcUser\Entity\User'));

        $registerForm = $this->getMockBuilder('ZfcUser\Form\Register')
                             ->disableOriginalConstructor()
                             ->getMock();
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

        $this->service->setRegisterForm($registerForm);

        $result = $this->service->register($expectArray);

        $this->assertSame($user, $result);
    }

    /**
     * @covers ZfcUser\Service\User::changePassword
     */
    public function testChangePasswordWithWrongOldPassword()
    {
        $data = array('newCredential' => 'zfcUser', 'credential' => 'zfcUserOld');

        $this->cryptoService
            ->expects($this->any())
            ->method('verify')
            ->will($this->returnValue(false));

        $user = $this->getMock('ZfcUser\Entity\User');
        $user->expects($this->any())
             ->method('getPassword')
             ->will($this->returnValue('wrongPassword'));

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

        $this->cryptoService
            ->expects($this->any())
            ->method('verify')
            ->will($this->returnValue(true));

        $user = $this->getMock('ZfcUser\Entity\User');
        $user->expects($this->any())
             ->method('getPassword')
             ->will($this->returnValue($data['credential']));

        $this->formHydrator
            ->expects($this->any())
            ->method('hydrate')
            ->will($this->returnValue($user));

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

        $this->cryptoService
            ->expects($this->any())
            ->method('verify')
            ->will($this->returnValue(true));

        $user = $this->getMock('ZfcUser\Entity\User');
        $user->expects($this->any())
             ->method('getPassword')
             ->will($this->returnValue($data['credential']));

        $this->formHydrator
            ->expects($this->any())
            ->method('hydrate')
            ->will($this->returnValue($user));

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

        $this->cryptoService
            ->expects($this->any())
            ->method('verify')
            ->will($this->returnValue(false));

        $user = $this->getMock('ZfcUser\Entity\User');
        $user->expects($this->any())
             ->method('getPassword')
             ->will($this->returnValue('wrongPassword'));

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
        $this->serviceManager->expects($this->once())
                             ->method('get')
                             ->with('zfcuser_user_mapper')
                             ->will($this->returnValue($this->mapper));

        $service = new Service;
        $service->setServiceManager($this->serviceManager);
        $this->assertInstanceOf('ZfcUser\Mapper\UserInterface', $service->getUserMapper());
    }

    /**
     * @covers ZfcUser\Service\User::getUserMapper
     * @covers ZfcUser\Service\User::setUserMapper
     */
    public function testSetGetUserMapper()
    {
        $this->assertSame($this->mapper, $this->service->getUserMapper());
    }

    /**
     * @covers ZfcUser\Service\User::getAuthService
     */
    public function testGetAuthService()
    {
        $this->serviceManager->expects($this->once())
             ->method('get')
             ->with('zfcuser_auth_service')
             ->will($this->returnValue($this->authService));

        $service = new Service;
        $service->setServiceManager($this->serviceManager);
        $this->assertInstanceOf(
            'Zend\Authentication\AuthenticationServiceInterface',
            $service->getAuthService()
        );
    }

    /**
     * @covers ZfcUser\Service\User::getAuthService
     * @covers ZfcUser\Service\User::setAuthService
     */
    public function testSetGetAuthService()
    {
        $this->assertSame($this->authService, $this->service->getAuthService());
    }

    /**
     * @covers ZfcUser\Service\User::getRegisterForm
     */
    public function testGetRegisterForm()
    {
        $form = $this->getMockBuilder('ZfcUser\Form\Register')->disableOriginalConstructor()->getMock();

        $this->serviceManager->expects($this->once())
             ->method('get')
             ->with('zfcuser_register_form')
             ->will($this->returnValue($form));

        $service = new Service;
        $service->setServiceManager($this->serviceManager);

        $result = $service->getRegisterForm();

        $this->assertInstanceOf('ZfcUser\Form\Register', $result);
        $this->assertSame($form, $result);
    }

    /**
     * @covers ZfcUser\Service\User::getRegisterForm
     * @covers ZfcUser\Service\User::setRegisterForm
     */
    public function testSetGetRegisterForm()
    {
        $form = $this->getMockBuilder('ZfcUser\Form\Register')->disableOriginalConstructor()->getMock();
        $this->service->setRegisterForm($form);

        $this->assertSame($form, $this->service->getRegisterForm());
    }

    /**
     * @covers ZfcUser\Service\User::getChangePasswordForm
     */
    public function testGetChangePasswordForm()
    {
        $form = $this->getMockBuilder('ZfcUser\Form\ChangePassword')->disableOriginalConstructor()->getMock();

        $this->serviceManager->expects($this->once())
             ->method('get')
             ->with('zfcuser_change_password_form')
             ->will($this->returnValue($form));

        $service = new Service;
        $service->setServiceManager($this->serviceManager);
        $this->assertInstanceOf('ZfcUser\Form\ChangePassword', $service->getChangePasswordForm());
    }

    /**
     * @covers ZfcUser\Service\User::getChangePasswordForm
     * @covers ZfcUser\Service\User::setChangePasswordForm
     */
    public function testSetGetChangePasswordForm()
    {
        $form = $this->getMockBuilder('ZfcUser\Form\ChangePassword')->disableOriginalConstructor()->getMock();
        $this->service->setChangePasswordForm($form);

        $this->assertSame($form, $this->service->getChangePasswordForm());
    }

    /**
     * @covers ZfcUser\Service\User::getOptions
     */
    public function testGetOptions()
    {
        $this->serviceManager->expects($this->once())
             ->method('get')
             ->with('zfcuser_module_options')
             ->will($this->returnValue($this->options));

        $service = new Service;
        $service->setServiceManager($this->serviceManager);
        $this->assertInstanceOf('ZfcUser\Options\ModuleOptions', $service->getOptions());
    }

    /**
     * @covers ZfcUser\Service\User::setOptions
     */
    public function testSetOptions()
    {
        $this->assertSame($this->options, $this->service->getOptions());
    }

    /**
     * @covers ZfcUser\Service\User::getServiceManager
     * @covers ZfcUser\Service\User::setServiceManager
     */
    public function testSetGetServiceManager()
    {
        $this->assertSame($this->serviceManager, $this->service->getServiceManager());
    }

    /**
     * @covers ZfcUser\Service\User::getFormHydrator
     */
    public function testGetFormHydrator()
    {
        $this->serviceManager->expects($this->once())
             ->method('get')
             ->with('zfcuser_user_hydrator')
             ->will($this->returnValue($this->formHydrator));

        $service = new Service;
        $service->setServiceManager($this->serviceManager);
        $this->assertInstanceOf(
            'ZfcUser\Mapper\HydratorInterface',
            $service->getFormHydrator()
        );
    }

    /**
     * @covers ZfcUser\Service\User::setFormHydrator
     */
    public function testSetFormHydrator()
    {
        $this->assertSame($this->formHydrator, $this->service->getFormHydrator());
    }
}
