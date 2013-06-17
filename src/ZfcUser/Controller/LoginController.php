<?php

namespace ZfcUser\Controller;

use ZfcUser\Service\LoginService;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;

class LoginController extends AbstractActionController
{
    /**
     * @var LoginService
     */
    protected $loginService;

    /**
     * @return Response
     */
    public function logoutAction()
    {
        if ($this->identity()) {
            $this->getLoginService()->logout();
        }
        return $this->redirect()->toRoute('zfc_user/login');
    }

    /**
     * @return array|Response
     */
    public function loginAction()
    {
        if ($this->identity()) {
            return $this->redirect()->toRoute('zfc_user');
        }
        $prg  = $this->prg();
        $form = $this->getLoginService()->getLoginForm();

        if ($prg instanceof Response) {
            return $prg;
        } elseif (false !== $prg) {
            if ($this->getLoginService()->login($prg)->isValid()) {
                return $this->redirect()->toRoute('zfc_user');
            }
        }

        return array('form' => $form);
    }

    /**
     * @return LoginService
     */
    public function getLoginService()
    {
        if (!$this->loginService) {
            $this->setLoginService($this->getServiceLocator()->get('ZfcUser\Service\LoginService'));
        }
        return $this->loginService;
    }

    /**
     * @param LoginService $loginService
     * @return LoginController
     */
    public function setLoginService($loginService)
    {
        $this->loginService = $loginService;
        return $this;
    }
}