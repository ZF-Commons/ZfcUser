<?php

namespace ZfcUserTest\Authentication\Adapter;

use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_TestCase as TestCase;
use ZfcUser\Authentication\Adapter\Db;

class DbTest extends TestCase
{
    const PASSWORD_COST_04 = '04';
    const PASSWORD_COST_10 = '10';

    /**
     * The object to be tested.
     *
     * @var Db
     */
    protected $db;

    /**
     * Mock of AuthEvent.
     *
     * @var MockObject
     */
    protected $authEvent;

    /**
     * Mock of Storage.
     *
     * @var MockObject
     */
    protected $storage;

    /**
     * Mock of Options.
     *
     * @var MockObject
     */
    protected $options;

    /**
     * Mock of Mapper.
     *
     * @var MockObject
     */
    protected $mapper;

    /**
     * @var MockObject
     */
    protected $hydrator;

    /**
     * @var MockObject
     */
    protected $bcrypt;

    /**
     * Mock of User.
     *
     * @var MockObject
     */
    protected $user;

    /**
     * Mock of ServiceManager.
     *
     * @var MockObject
     */
    protected $services;

    protected function setUp()
    {
        $this->options   = $this->getMock('ZfcUser\Options\ModuleOptions');
        $this->mapper    = $this->getMockForAbstractClass(
            'ZfcUser\Mapper\UserInterface'
        );
        $this->user      = $this->getMockForAbstractClass(
            'ZfcUser\Entity\UserInterface'
        );
        $this->storage   = $this->getMockForAbstractClass(
            'Zend\Authentication\Storage\StorageInterface'
        );
        $this->authEvent = $this->getMock(
            'ZfcUser\Authentication\Adapter\AdapterChainEvent'
        );

        $this->bcrypt    = $this->getMock('Zend\Crypt\Password\Bcrypt');
        $this->hydrator  = $this->getMockForAbstractClass(
            'ZfcUser\Mapper\HydratorInterface'
        );
        $this->hydrator->expects($this->any())
            ->method('getCryptoService')
            ->will($this->returnValue($this->bcrypt));

        $this->services  = $this->getMock('Zend\ServiceManager\ServiceManager');
        $this->services->expects($this->any())
            ->method('get')
            ->will($this->returnValueMap(array(
                array('zfcuser_module_options', true, $this->options),
                array('zfcuser_user_mapper', true, $this->mapper),
                array('zfcuser_user_hydrator', true, $this->hydrator),
            )));

        $this->db = new Db;
        $this->db->setServiceManager($this->services);
        $this->db->setStorage($this->storage);

        $sessionManager = $this->getMock('Zend\Session\SessionManager');
        \Zend\Session\AbstractContainer::setDefaultManager($sessionManager);
    }

    /**
     * @covers ZfcUser\Authentication\Adapter\Db::logout
     */
    public function testLogout()
    {
        $this->storage->expects($this->once())->method('clear');
        $this->db->logout($this->authEvent);
    }

    /**
     * @covers ZfcUser\Authentication\Adapter\Db::Authenticate
     */
    public function testAuthenticateWhenSatisfies()
    {
        $this->authEvent->expects($this->once())
                        ->method('setIdentity')
                        ->with('ZfcUser')
                        ->will($this->returnValue($this->authEvent));
        $this->authEvent->expects($this->once())
                        ->method('setCode')
                        ->with(\Zend\Authentication\Result::SUCCESS)
                        ->will($this->returnValue($this->authEvent));
        $this->authEvent->expects($this->once())
                        ->method('setMessages')
                        ->with(array('Authentication successful.'))
                        ->will($this->returnValue($this->authEvent));

        $this->storage->expects($this->at(0))
            ->method('read')
            ->will($this->returnValue(array('is_satisfied' => true)));
        $this->storage->expects($this->at(1))
            ->method('read')
            ->will($this->returnValue(array('identity' => 'ZfcUser')));

        $result = $this->db->authenticate($this->authEvent);
        $this->assertNull($result);
    }

    /**
     * @covers ZfcUser\Authentication\Adapter\Db::Authenticate
     */
    public function testAuthenticateNoUserObject()
    {
        $this->setAuthenticationCredentials();

        $this->options->expects($this->once())
            ->method('getAuthIdentityFields')
            ->will($this->returnValue(array()));

        $this->authEvent->expects($this->once())
            ->method('setCode')
            ->with(\Zend\Authentication\Result::FAILURE_IDENTITY_NOT_FOUND)
            ->will($this->returnValue($this->authEvent));
        $this->authEvent->expects($this->once(1))
            ->method('setMessages')
            ->with(array('A record with the supplied identity could not be found.'))
            ->will($this->returnValue($this->authEvent));

        $result = $this->db->authenticate($this->authEvent);

        $this->assertFalse($result);
        $this->assertFalse($this->db->isSatisfied());
    }

    /**
     * @covers ZfcUser\Authentication\Adapter\Db::Authenticate
     */
    public function testAuthenticationUserStateEnabledUserButUserStateNotInArray()
    {
        $this->setAuthenticationCredentials();
        $this->setAuthenticationUser();

        $this->options->expects($this->once())
            ->method('getEnableUserState')
            ->will($this->returnValue(true));
        $this->options->expects($this->once())
            ->method('getAllowedLoginStates')
            ->will($this->returnValue(array(2, 3)));

        $this->authEvent->expects($this->once())
            ->method('setCode')
            ->with(\Zend\Authentication\Result::FAILURE_UNCATEGORIZED)
            ->will($this->returnValue($this->authEvent));
        $this->authEvent->expects($this->once())
            ->method('setMessages')
            ->with(array('A record with the supplied identity is not active.'))
            ->will($this->returnValue($this->authEvent));

        $this->user->expects($this->once())
            ->method('getState')
            ->will($this->returnValue(1));

        $result = $this->db->authenticate($this->authEvent);

        $this->assertFalse($result);
        $this->assertFalse($this->db->isSatisfied());
    }

    /**
     * @covers ZfcUser\Authentication\Adapter\Db::Authenticate
     */
    public function testAuthenticateWithWrongPassword()
    {
        $this->setAuthenticationCredentials();
        $this->setAuthenticationUser();

        $this->options->expects($this->once())
            ->method('getEnableUserState')
            ->will($this->returnValue(false));

        $this->bcrypt->expects($this->once())
            ->method('verify')
            ->will($this->returnValue(false));

        $this->authEvent->expects($this->once())
            ->method('setCode')
            ->with(\Zend\Authentication\Result::FAILURE_CREDENTIAL_INVALID)
            ->will($this->returnValue($this->authEvent));
        $this->authEvent->expects($this->once(1))
            ->method('setMessages')
            ->with(array('Supplied credential is invalid.'));

        $result = $this->db->authenticate($this->authEvent);

        $this->assertFalse($result);
        $this->assertFalse($this->db->isSatisfied());
    }

    /**
     * @covers ZfcUser\Authentication\Adapter\Db::Authenticate
     */
    public function testAuthenticationAuthenticatesWithEmail()
    {
        $this->setAuthenticationCredentials('zfc-user@zf-commons.io');
        $this->setAuthenticationEmail();

        $this->options->expects($this->once())
            ->method('getEnableUserState')
            ->will($this->returnValue(false));

        $this->bcrypt->expects($this->once())
            ->method('verify')
            ->will($this->returnValue(true));
        $this->bcrypt->expects($this->any())
            ->method('getCost')
            ->will($this->returnValue(static::PASSWORD_COST_04));

        $this->user->expects($this->exactly(2))
            ->method('getPassword')
            ->will($this->returnValue('$2a$04$5kq1mnYWbww8X.rIj7eOVOHXtvGw/peefjIcm0lDGxRTEjm9LnOae'));
        $this->user->expects($this->once())
                   ->method('getId')
                   ->will($this->returnValue(1));

        $this->storage->expects($this->any())
                      ->method('getNameSpace')
                      ->will($this->returnValue('test'));

        $this->authEvent->expects($this->once())
                        ->method('setIdentity')
                        ->with(1)
                        ->will($this->returnValue($this->authEvent));
        $this->authEvent->expects($this->once())
                        ->method('setCode')
                        ->with(\Zend\Authentication\Result::SUCCESS)
                        ->will($this->returnValue($this->authEvent));
        $this->authEvent->expects($this->once())
                        ->method('setMessages')
                        ->with(array('Authentication successful.'))
                        ->will($this->returnValue($this->authEvent));

        $result = $this->db->authenticate($this->authEvent);
    }

    /**
     * @covers ZfcUser\Authentication\Adapter\Db::Authenticate
     */
    public function testAuthenticationAuthenticates()
    {
        $this->setAuthenticationCredentials();
        $this->setAuthenticationUser();

        $this->options->expects($this->once())
             ->method('getEnableUserState')
             ->will($this->returnValue(true));

        $this->options->expects($this->once())
             ->method('getAllowedLoginStates')
             ->will($this->returnValue(array(1, 2, 3)));

        $this->bcrypt->expects($this->once())
            ->method('verify')
            ->will($this->returnValue(true));
        $this->bcrypt->expects($this->any())
            ->method('getCost')
            ->will($this->returnValue(static::PASSWORD_COST_04));

        $this->user->expects($this->exactly(2))
                   ->method('getPassword')
                   ->will($this->returnValue('$2a$04$5kq1mnYWbww8X.rIj7eOVOHXtvGw/peefjIcm0lDGxRTEjm9LnOae'));
        $this->user->expects($this->once())
                   ->method('getId')
                   ->will($this->returnValue(1));
        $this->user->expects($this->once())
                   ->method('getState')
                   ->will($this->returnValue(1));

        $this->storage->expects($this->any())
                      ->method('getNameSpace')
                      ->will($this->returnValue('test'));

        $this->authEvent->expects($this->once())
                        ->method('setIdentity')
                        ->with(1)
                        ->will($this->returnValue($this->authEvent));
        $this->authEvent->expects($this->once())
                        ->method('setCode')
                        ->with(\Zend\Authentication\Result::SUCCESS)
                        ->will($this->returnValue($this->authEvent));
        $this->authEvent->expects($this->once())
                        ->method('setMessages')
                        ->with(array('Authentication successful.'))
                        ->will($this->returnValue($this->authEvent));

        $result = $this->db->authenticate($this->authEvent);
    }

    /**
     * @covers ZfcUser\Authentication\Adapter\Db::updateUserPasswordHash
     */
    public function testUpdateUserPasswordHashWithSameCost()
    {
        $this->user->expects($this->once())
            ->method('getPassword')
            ->will($this->returnValue('$2a$10$x05G2P803MrB3jaORBXBn.QHtiYzGQOBjQ7unpEIge.Mrz6c3KiVm'));

        $this->bcrypt->expects($this->once())
            ->method('getCost')
            ->will($this->returnValue(static::PASSWORD_COST_10));

        $this->hydrator->expects($this->never())->method('hydrate');
        $this->mapper->expects($this->never())->method('update');

        $method = new \ReflectionMethod(
            'ZfcUser\Authentication\Adapter\Db',
            'updateUserPasswordHash'
        );
        $method->setAccessible(true);
        $method->invoke($this->db, $this->user, 'ZfcUser', $this->bcrypt);
    }

    /**
     * @covers ZfcUser\Authentication\Adapter\Db::updateUserPasswordHash
     */
    public function testUpdateUserPasswordHashWithoutSameCost()
    {
        $this->user->expects($this->once())
            ->method('getPassword')
            ->will($this->returnValue('$2a$10$x05G2P803MrB3jaORBXBn.QHtiYzGQOBjQ7unpEIge.Mrz6c3KiVm'));

        $this->bcrypt->expects($this->once())
            ->method('getCost')
            ->will($this->returnValue(static::PASSWORD_COST_04));

        $this->hydrator->expects($this->once())
            ->method('hydrate')
            ->with(array('password' => 'ZfcUserNew'), $this->user)
            ->will($this->returnValue($this->user));

        $this->mapper->expects($this->once())
            ->method('update')
            ->with($this->user);

        $method = new \ReflectionMethod(
            'ZfcUser\Authentication\Adapter\Db',
            'updateUserPasswordHash'
        );
        $method->setAccessible(true);
        $method->invoke($this->db, $this->user, 'ZfcUserNew', $this->bcrypt);
    }

    /**
     * @covers ZfcUser\Authentication\Adapter\Db::getCredentialPreprocessor
     * @covers ZfcUser\Authentication\Adapter\Db::setCredentialPreprocessor
     */
    public function testSetValidPreprocessCredential()
    {
        $callable = function () {
            // no-op
        };
        $this->db->setCredentialPreprocessor($callable);
        $this->assertSame($callable, $this->db->getCredentialPreprocessor());
    }

    /**
     * @covers ZfcUser\Authentication\Adapter\Db::setCredentialPreprocessor
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Credential Preprocessor must be callable, [boolean] given
     */
    public function testSetInvalidPreprocessCredential()
    {
        $this->db->setCredentialPreprocessor(false);
    }

    /**
     * @covers ZfcUser\Authentication\Adapter\Db::preprocessCredential
     * @covers ZfcUser\Authentication\Adapter\Db::setCredentialPreprocessor
     */
    public function testPreprocessCredentialWithCallable()
    {
        $expected = 'processed';
        $this->db->setCredentialPreprocessor(function ($credential) use ($expected) {
            return $expected;
        });
        $this->assertSame($expected, $this->db->preprocessCredential('ZfcUser'));
    }

    /**
     * @covers ZfcUser\Authentication\Adapter\Db::preprocessCredential
     */
    public function testPreprocessCredentialWithoutCallable()
    {
        $this->assertSame('zfcUser', $this->db->preprocessCredential('zfcUser'));
    }

    /**
     * @covers ZfcUser\Authentication\Adapter\Db::getServiceManager
     * @covers ZfcUser\Authentication\Adapter\Db::setServiceManager
     */
    public function testGetServiceManager()
    {
        $this->assertSame($this->services, $this->db->getServiceManager());
    }

    /**
     * @depends testGetServiceManager
     * @covers ZfcUser\Authentication\Adapter\Db::getOptions
     */
    public function testLazyLoadOptions()
    {
        $this->assertEquals($this->options, $this->db->getOptions());
    }

    /**
     * @covers ZfcUser\Authentication\Adapter\Db::setOptions
     * @covers ZfcUser\Authentication\Adapter\Db::getOptions
     */
    public function testSetOptions()
    {
        $options = new \ZfcUser\Options\ModuleOptions;
        $options->setLoginRedirectRoute('zfcUser');

        $this->db->setOptions($options);

        $this->assertInstanceOf('ZfcUser\Options\ModuleOptions', $this->db->getOptions());
        $this->assertSame('zfcUser', $this->db->getOptions()->getLoginRedirectRoute());
    }

    /**
     * @depends testGetServiceManager
     * @covers ZfcUser\Authentication\Adapter\Db::getMapper
     */
    public function testLazyLoadMapper()
    {
        $this->assertEquals($this->mapper, $this->db->getMapper());
    }

    /**
     * @covers ZfcUser\Authentication\Adapter\Db::setMapper
     * @covers ZfcUser\Authentication\Adapter\Db::getMapper
     */
    public function testSetMapper()
    {
        $mapper = new \ZfcUser\Mapper\User;
        $mapper->setTableName('zfcUser');

        $this->db->setMapper($mapper);

        $this->assertInstanceOf('ZfcUser\Mapper\User', $this->db->getMapper());
        $this->assertSame('zfcUser', $this->db->getMapper()->getTableName());
    }

    /**
     * @depends testGetServiceManager
     * @covers ZfcUser\Authentication\Adapter\Db::getHydrator
     */
    public function testLazyLoadHydrator()
    {
        $this->assertEquals($this->hydrator, $this->db->getHydrator());
    }

    /**
     * @covers ZfcUser\Authentication\Adapter\Db::setHydrator
     * @covers ZfcUser\Authentication\Adapter\Db::getHydrator
     */
    public function testSetHydrator()
    {
        $this->db->setHydrator($this->hydrator);
        $this->assertSame($this->hydrator, $this->db->getHydrator());
    }

    protected function setAuthenticationEmail()
    {
        $this->mapper->expects($this->once())
            ->method('findByEmail')
            ->with('zfc-user@zf-commons.io')
            ->will($this->returnValue($this->user));

        $this->options->expects($this->once())
            ->method('getAuthIdentityFields')
            ->will($this->returnValue(array('email')));
    }

    protected function setAuthenticationUser()
    {
        $this->mapper->expects($this->once())
            ->method('findByUsername')
            ->with('ZfcUser')
            ->will($this->returnValue($this->user));

        $this->options->expects($this->once())
            ->method('getAuthIdentityFields')
            ->will($this->returnValue(array('username')));
    }

    protected function setAuthenticationCredentials($identity = 'ZfcUser', $credential = 'ZfcUserPassword')
    {
        $this->storage->expects($this->at(0))
            ->method('read')
            ->will($this->returnValue(array('is_satisfied' => false)));

        $post = $this->getMock('Zend\Stdlib\Parameters');
        $post->expects($this->at(0))
            ->method('get')
            ->with('identity')
            ->will($this->returnValue($identity));
        $post->expects($this->at(1))
            ->method('get')
            ->with('credential')
            ->will($this->returnValue($credential));

        $request = $this->getMock('Zend\Http\Request');
        $request->expects($this->exactly(2))
            ->method('getPost')
            ->will($this->returnValue($post));

        $this->authEvent->expects($this->exactly(2))
            ->method('getRequest')
            ->will($this->returnValue($request));
    }
}
