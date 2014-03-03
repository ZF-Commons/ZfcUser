<?php
namespace ZfcUserTest\Factory;

use Zend\ServiceManager\ServiceManager;
use ZfcUser\Factory\ModuleOptionsFactory;

class ModuleOptionsFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = new ServiceManager;
        $serviceManager->setService('Config', array());
        $factory = new ModuleOptionsFactory;
        $this->assertInstanceOf('ZfcUser\Options\ModuleOptions', $factory->createService($serviceManager));
    }
}
