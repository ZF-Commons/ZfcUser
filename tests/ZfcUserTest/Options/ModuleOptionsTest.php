<?php

namespace ZfcUserTest\Options;

use ZfcUser\Options\ModuleOptions as Options;

class ModuleOptionsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Options $options
     */
    protected $options;

    public function setUp()
    {
        $options = new Options;
        $this->options = $options;
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getLoginRedirectRoute
     * @covers ZfcUser\Options\ModuleOptions::setLoginRedirectRoute
     */
    public function testSetGetLoginRedirectRoute()
    {
        $this->options->setLoginRedirectRoute('zfcUserRoute');
        $this->assertEquals('zfcUserRoute', $this->options->getLoginRedirectRoute());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getLoginRedirectRoute
     */
    public function testGetLoginRedirectRoute()
    {
        $this->assertEquals('zfcuser', $this->options->getLoginRedirectRoute());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getLogoutRedirectRoute
     * @covers ZfcUser\Options\ModuleOptions::setLogoutRedirectRoute
     */
    public function testSetGetLogoutRedirectRoute()
    {
        $this->options->setLogoutRedirectRoute('zfcUserRoute');
        $this->assertEquals('zfcUserRoute', $this->options->getLogoutRedirectRoute());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getLogoutRedirectRoute
     */
    public function testGetLogoutRedirectRoute()
    {
        $this->assertSame('zfcuser/login', $this->options->getLogoutRedirectRoute());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getUseRedirectParameterIfPresent
     * @covers ZfcUser\Options\ModuleOptions::setUseRedirectParameterIfPresent
     */
    public function testSetGetUseRedirectParameterIfPresent()
    {
        $this->options->setUseRedirectParameterIfPresent(false);
        $this->assertFalse($this->options->getUseRedirectParameterIfPresent());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getUseRedirectParameterIfPresent
     */
    public function testGetUseRedirectParameterIfPresent()
    {
        $this->assertTrue($this->options->getUseRedirectParameterIfPresent());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getUserLoginWidgetViewTemplate
     * @covers ZfcUser\Options\ModuleOptions::setUserLoginWidgetViewTemplate
     */
    public function testSetGetUserLoginWidgetViewTemplate()
    {
        $this->options->setUserLoginWidgetViewTemplate('zfcUser.phtml');
        $this->assertEquals('zfcUser.phtml', $this->options->getUserLoginWidgetViewTemplate());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getUserLoginWidgetViewTemplate
     */
    public function testGetUserLoginWidgetViewTemplate()
    {
        $this->assertEquals('zfc-user/user/login.phtml', $this->options->getUserLoginWidgetViewTemplate());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getEnableRegistration
     * @covers ZfcUser\Options\ModuleOptions::setEnableRegistration
     */
    public function testSetGetEnableRegistration()
    {
        $this->options->setEnableRegistration(false);
        $this->assertFalse($this->options->getEnableRegistration());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getEnableRegistration
     */
    public function testGetEnableRegistration()
    {
        $this->assertTrue($this->options->getEnableRegistration());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getLoginFormTimeout
     * @covers ZfcUser\Options\ModuleOptions::setLoginFormTimeout
     */
    public function testSetGetLoginFormTimeout()
    {
        $this->options->setLoginFormTimeout(100);
        $this->assertEquals(100, $this->options->getLoginFormTimeout());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getLoginFormTimeout
     */
    public function testGetLoginFormTimeout()
    {
        $this->assertEquals(300, $this->options->getLoginFormTimeout());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getUserFormTimeout
     * @covers ZfcUser\Options\ModuleOptions::setUserFormTimeout
     */
    public function testSetGetUserFormTimeout()
    {
        $this->options->setUserFormTimeout(100);
        $this->assertEquals(100, $this->options->getUserFormTimeout());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getUserFormTimeout
     */
    public function testGetUserFormTimeout()
    {
        $this->assertEquals(300, $this->options->getUserFormTimeout());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getLoginAfterRegistration
     * @covers ZfcUser\Options\ModuleOptions::setLoginAfterRegistration
     */
    public function testSetGetLoginAfterRegistration()
    {
        $this->options->setLoginAfterRegistration(false);
        $this->assertFalse($this->options->getLoginAfterRegistration());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getLoginAfterRegistration
     */
    public function testGetLoginAfterRegistration()
    {
        $this->assertTrue($this->options->getLoginAfterRegistration());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getEnableUserState
     * @covers ZfcUser\Options\ModuleOptions::setEnableUserState
     */
    public function testSetGetEnableUserState()
    {
        $this->options->setEnableUserState(true);
        $this->assertTrue($this->options->getEnableUserState());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getEnableUserState
     */
    public function testGetEnableUserState()
    {
        $this->assertFalse($this->options->getEnableUserState());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getDefaultUserState
     */
    public function testGetDefaultUserState()
    {
        $this->assertEquals(1, $this->options->getDefaultUserState());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getDefaultUserState
     * @covers ZfcUser\Options\ModuleOptions::setDefaultUserState
     */
    public function testSetGetDefaultUserState()
    {
        $this->options->setDefaultUserState(3);
        $this->assertEquals(3, $this->options->getDefaultUserState());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getAllowedLoginStates
     */
    public function testGetAllowedLoginStates()
    {
        $this->assertEquals(array(null, 1), $this->options->getAllowedLoginStates());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getAllowedLoginStates
     * @covers ZfcUser\Options\ModuleOptions::setAllowedLoginStates
     */
    public function testSetGetAllowedLoginStates()
    {
        $this->options->setAllowedLoginStates(array(2, 5, null));
        $this->assertEquals(array(2, 5, null), $this->options->getAllowedLoginStates());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getAuthAdapters
     */
    public function testGetAuthAdapters()
    {
        $this->assertEquals(array(100 => 'ZfcUser\Authentication\Adapter\Db'), $this->options->getAuthAdapters());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getAuthAdapters
     * @covers ZfcUser\Options\ModuleOptions::setAuthAdapters
     */
    public function testSetGetAuthAdapters()
    {
        $this->options->setAuthAdapters(array(40 => 'SomeAdapter'));
        $this->assertEquals(array(40 => 'SomeAdapter'), $this->options->getAuthAdapters());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getAuthIdentityFields
     * @covers ZfcUser\Options\ModuleOptions::setAuthIdentityFields
     */
    public function testSetGetAuthIdentityFields()
    {
        $this->options->setAuthIdentityFields(array('username'));
        $this->assertEquals(array('username'), $this->options->getAuthIdentityFields());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getAuthIdentityFields
     */
    public function testGetAuthIdentityFields()
    {
        $this->assertEquals(array('email'), $this->options->getAuthIdentityFields());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getEnableUsername
     */
    public function testGetEnableUsername()
    {
        $this->assertFalse($this->options->getEnableUsername());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getEnableUsername
     * @covers ZfcUser\Options\ModuleOptions::setEnableUsername
     */
    public function testSetGetEnableUsername()
    {
        $this->options->setEnableUsername(true);
        $this->assertTrue($this->options->getEnableUsername());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getEnableDisplayName
     * @covers ZfcUser\Options\ModuleOptions::setEnableDisplayName
     */
    public function testSetGetEnableDisplayName()
    {
        $this->options->setEnableDisplayName(true);
        $this->assertTrue($this->options->getEnableDisplayName());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getEnableDisplayName
     */
    public function testGetEnableDisplayName()
    {
        $this->assertFalse($this->options->getEnableDisplayName());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getUseRegistrationFormCaptcha
     * @covers ZfcUser\Options\ModuleOptions::setUseRegistrationFormCaptcha
     */
    public function testSetGetUseRegistrationFormCaptcha()
    {
        $this->options->setUseRegistrationFormCaptcha(true);
        $this->assertTrue($this->options->getUseRegistrationFormCaptcha());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getUseRegistrationFormCaptcha
     */
    public function testGetUseRegistrationFormCaptcha()
    {
        $this->assertFalse($this->options->getUseRegistrationFormCaptcha());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getUserEntityClass
     * @covers ZfcUser\Options\ModuleOptions::setUserEntityClass
     */
    public function testSetGetUserEntityClass()
    {
        $this->options->setUserEntityClass('zfcUser');
        $this->assertEquals('zfcUser', $this->options->getUserEntityClass());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getUserEntityClass
     */
    public function testGetUserEntityClass()
    {
        $this->assertEquals('ZfcUser\Entity\User', $this->options->getUserEntityClass());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getPasswordCost
     * @covers ZfcUser\Options\ModuleOptions::setPasswordCost
     */
    public function testSetGetPasswordCost()
    {
        $this->options->setPasswordCost(10);
        $this->assertEquals(10, $this->options->getPasswordCost());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getPasswordCost
     */
    public function testGetPasswordCost()
    {
        $this->assertEquals(14, $this->options->getPasswordCost());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getTableName
     * @covers ZfcUser\Options\ModuleOptions::setTableName
     */
    public function testSetGetTableName()
    {
        $this->options->setTableName('zfcUser');
        $this->assertEquals('zfcUser', $this->options->getTableName());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getTableName
     */
    public function testGetTableName()
    {
        $this->assertEquals('user', $this->options->getTableName());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getFormCaptchaOptions
     * @covers ZfcUser\Options\ModuleOptions::setFormCaptchaOptions
     */
    public function testSetGetFormCaptchaOptions()
    {
        $expected = array(
            'class'   => 'someClass',
            'options' => array(
                'anOption' => 3,
            ),
        );
        $this->options->setFormCaptchaOptions($expected);
        $this->assertEquals($expected, $this->options->getFormCaptchaOptions());
    }

    /**
     * @covers ZfcUser\Options\ModuleOptions::getFormCaptchaOptions
     */
    public function testGetFormCaptchaOptions()
    {
        $expected = array(
            'class'   => 'figlet',
            'options' => array(
                'wordLen'    => 5,
                'expiration' => 300,
                'timeout'    => 300,
            ),
        );
        $this->assertEquals($expected, $this->options->getFormCaptchaOptions());
    }
}
