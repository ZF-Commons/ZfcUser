<?php
namespace ZfcUserTest\Factory\View\Helper;

use ZfcUser\Factory\View\Helper\LoginWidgetFactory;
use Zend\ServiceManager\ServiceManager;
use ZfcUser\Options\ModuleOptions;
use ZfcUser\Form\Login as LoginForm;

class LoginWidgetFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = new ServiceManager;
        $options = new ModuleOptions;
        $serviceManager->setService('zfcuser_module_options', $options);
        $serviceManager->setService('zfcuser_login_form', new LoginForm(null, $options));
        $factory = new LoginWidgetFactory;
        $helpers = $this->getMock('Zend\ServiceManager\AbstractPluginManager');
        $helpers->expects($this->any())
            ->method('getServiceLocator')
            ->will($this->returnValue($serviceManager));
        $this->assertInstanceOf('ZfcUser\View\Helper\ZfcUserLoginWidget', $factory->createService($helpers));
    }
}
