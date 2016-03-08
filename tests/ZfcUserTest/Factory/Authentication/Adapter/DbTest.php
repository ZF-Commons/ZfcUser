<?php
namespace ZfcUserTest\Authentication\Adapter\Factory;

use Zend\ServiceManager\ServiceManager;
use ZfcUser\Factory\Authentication\Adapter\Db;
use ZfcUser\Mapper\User;
use ZfcUser\Options\ModuleOptions;

class DbTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = new ServiceManager;
        $serviceManager->setService('zfcuser_user_mapper', new User());
        $serviceManager->setService('zfcuser_module_options', new ModuleOptions);

        $factory = new Db;

        $this->assertInstanceOf('ZfcUser\Authentication\Adapter\Db', $factory->createService($serviceManager));
    }
}
