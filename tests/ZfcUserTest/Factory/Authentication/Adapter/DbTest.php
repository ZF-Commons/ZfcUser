<?php
namespace ZfcUserTest\Authentication\Adapter\Factory;

use Zend\ServiceManager\ServiceManager;
use ZfcUser\Factory\Authentication\Adapter\Db;
use ZfcUser\Options\ModuleOptions;

class DbTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = new ServiceManager;

        $mapper = $this->getMockForAbstractClass(
            'ZfcUser\Mapper\UserInterface'
        );
        $serviceManager->setService('zfcuser_user_mapper', $mapper);
        $hydrator  = $this->getMockForAbstractClass(
            'ZfcUser\Mapper\HydratorInterface'
        );
        $serviceManager->setService('zfcuser_user_hydrator', $hydrator);
        $options = new ModuleOptions;
        $serviceManager->setService('zfcuser_module_options', $options);

        $factory = new Db;

        $this->assertInstanceOf('ZfcUser\Authentication\Adapter\Db', $factory->createService($serviceManager));
    }
}
