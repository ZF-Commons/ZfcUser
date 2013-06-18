<?php

namespace ZfcUser\Controller;

use ZfcUser\Service\RegisterService;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;

class RegisterController extends AbstractActionController
{
    /**
     * @var RegisterService
     */
    protected $registerService;

    /**
     * @return array|Response
     */
    public function registerAction()
    {
        if ($this->identity()) {
            return $this->redirect()->toRoute('zfc_user');
        }
        $prg  = $this->prg();
        $form = $this->getRegisterService()->getForm();

        if ($prg instanceof Response) {
            return $prg;
        } elseif (false !== $prg) {
            if ($this->getRegisterService()->register($prg)) {
                return $this->redirect()->toRoute('zfc_user');
            }
        }

        return array('form' => $form);
    }

    /**
     * @return RegisterService
     */
    public function getRegisterService()
    {
        if (!$this->registerService) {
            $this->setRegisterService($this->getServiceLocator()->get('ZfcUser\Service\RegisterService'));
        }
        return $this->registerService;
    }

    /**
     * @param RegisterService $registerService
     * @return RegisterController
     */
    public function setRegisterService($registerService)
    {
        $this->registerService = $registerService;
        return $this;
    }
}