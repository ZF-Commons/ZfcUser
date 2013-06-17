<?php

namespace ZfcUserTest\Controller;

class UserControllerTest extends AbstractControllerTestCase
{
    /**
     * @covers \ZfcUser\Controller\UserController::indexAction
     */
    public function testIndexAction()
    {
        $this->dispatch('/user');
        $this->assertControllerName('ZfcUser\Controller\UserController');
        $this->assertActionName('index');
        $this->assertRedirectTo('/user/login');

        $this->loginUser();

        $this->dispatch('/user');
        $this->assertActionName('index');
        $this->assertControllerName('ZfcUser\Controller\UserController');
        $this->assertNotRedirect();
    }
}