<?php

namespace ZfcUser\Controller;

use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;

class RegisterController extends AbstractActionController
{
    /**
     * @return array|Response
     */
    public function registerAction()
    {
        if ($this->identity()) {
            return $this->redirect()->toRoute('zfc_user');
        }
        $prg  = $this->prg();
        $form = $this->getRegisterExtension()->getForm();

        if ($prg instanceof Response) {
            return $prg;
        } elseif (false !== $prg) {
            if ($this->getRegisterExtension()->register($prg)) {
                return $this->redirect()->toRoute('zfc_user');
            }
        }

        return array('form' => $form);
    }

    /**
     * @return \ZfcUser\Extension\Register
     */
    public function getRegisterExtension()
    {
        return $this->plugin('zfcUserExtension')->get('register');
    }
}