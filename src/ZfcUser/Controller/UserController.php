<?php

namespace ZfcUser\Controller;

use ZfcUser\Service\LoginService;
use ZfcUser\Service\RegisterService;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;

class UserController extends AbstractActionController
{
    /**
     * @var LoginService
     */
    protected $loginService;

    /**
     * @var RegisterService
     */
    protected $registerService;

    public function indexAction()
    {
        if (!$this->identity()) {
            return $this->redirect()->toRoute('zfc_user/login');
        }
        return array();
    }

    public function logoutAction()
    {
        if ($this->identity()) {
            $this->getLoginService()->logout();
        }
        $this->redirect()->toRoute('zfc_user/login');
    }

    public function loginAction()
    {
        if ($this->identity()) {
            $this->redirect()->toRoute('zfc_user');
        }
        $prg  = $this->prg();
        $form = $this->getLoginService()->getForm();

        if ($prg instanceof Response) {
            return $prg;
        } elseif (false !== $prg) {
            $this->getLoginService()->login($prg);
            return $this->redirect()->toRoute('zfc_user');
        }

        return array('form' => $form);
    }

    public function registerAction()
    {
        $prg  = $this->prg();
        $form = $this->getRegisterService()->getForm();

        if ($prg instanceof Response) {
            return $prg;
        } elseif (false !== $prg) {
            $this->getRegisterService()->register($prg);
            return $this->redirect()->toRoute('zfc_user');
        }

        return array('form' => $form);
    }

    /**
     * @return LoginService
     */
    public function getLoginService()
    {
        if (!$this->loginService) {
            $this->loginService = $this->getServiceLocator()->get('ZfcUser\Service\LoginService');
        }
        return $this->loginService;
    }

    /**
     * @param LoginService $loginService
     * @return UserController
     */
    public function setLoginService($loginService)
    {
        $this->loginService = $loginService;
        return $this;
    }

    /**
     * @return RegisterService
     */
    public function getRegisterService()
    {
        if (!$this->registerService) {
            $this->registerService = $this->getServiceLocator()->get('ZfcUser\Service\RegisterService');
        }
        return $this->registerService;
    }

    /**
     * @param RegisterService $registerService
     * @return UserController
     */
    public function setRegisterService($registerService)
    {
        $this->registerService = $registerService;
        return $this;
    }
}