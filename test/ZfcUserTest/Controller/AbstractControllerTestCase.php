<?php

namespace ZfcUserTest\Controller;

use SpiffyTest\Controller\AbstractHttpControllerTestCase;
use ZfcUserTest\Asset\User;

abstract class AbstractControllerTestCase extends AbstractHttpControllerTestCase
{
    protected function logoutUser()
    {
        $auth = $this->getAuth();
        $auth->clearIdentity();

        $this->reset();
    }

    protected function loginUser()
    {
        $this->reset();

        $auth = $this->getAuth();
        $auth->getStorage()->write(new User());
    }

    /**
     * @return \Zend\Authentication\AuthenticationService
     */
    protected function getAuth()
    {
        return $this->getApplicationServiceLocator()->get('Zend\Authentication\AuthenticationService');
    }
}