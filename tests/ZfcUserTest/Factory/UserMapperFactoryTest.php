<?php
namespace ZfcUserTest\Factory;

use Zend\ServiceManager\ServiceManager;
use ZfcUser\Factory\UserMapperFactory;
use ZfcUser\Options\ModuleOptions;

class UserMapperFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = new ServiceManager;
        $options = new ModuleOptions;
        $serviceManager->setService('zfcuser_module_options', $options);
        $serviceManager->setService('zfcuser_user_hydrator', $this->getMock('Zend\Stdlib\Hydrator\HydratorInterface'));
        $dbAdapter = $this->getMockBuilder('Zend\Db\Adapter\Adapter')->disableOriginalConstructor()->getMock();
        $serviceManager->setService('zfcuser_zend_db_adapter', $dbAdapter);
        $factory = new UserMapperFactory;
        $this->assertInstanceOf('ZfcUser\Mapper\User', $factory->createService($serviceManager)); 
    }
}
