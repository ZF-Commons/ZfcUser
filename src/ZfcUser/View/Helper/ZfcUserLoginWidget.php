<?php

namespace ZfcUser\View\Helper;

use Zend\View\Helper\AbstractHelper;
use ZfcUser\Form\Login as LoginForm;
use Zend\View\Model\ViewModel;

class ZfcUserLoginWidget extends AbstractHelper
{
    /**
     * Login Form
     * @var LoginForm
     */
    protected $loginForm;

    /**
     * $var string template used for view
     */
    protected $viewTemplate;
    /**
     * __invoke
     *
     * @access public
     * @param array $options array of options
     * @return string
     */
    public function __invoke($options = array())
    {
        $options += array(
            'render' => true,
            'redirect' => false,
            'enableRegistration' => false,
        );

        $vm = new ViewModel(array(
            'loginForm' => $this->getLoginForm(),
            'redirect'  => $options['redirect'],
            'enableRegistration'  => $options['enableRegistration'],
        ));
        $vm->setTemplate($this->viewTemplate);
        if ($options['render']) {
            return $this->getView()->render($vm);
        }

        return $vm;
    }

    /**
     * Retrieve Login Form Object
     * @return LoginForm
     */
    public function getLoginForm()
    {
        return $this->loginForm;
    }

    /**
     * Inject Login Form Object
     * @param LoginForm $loginForm
     * @return ZfcUserLoginWidget
     */
    public function setLoginForm(LoginForm $loginForm)
    {
        $this->loginForm = $loginForm;
        return $this;
    }

    /**
     * @param string $viewTemplate
     * @return ZfcUserLoginWidget
     */
    public function setViewTemplate($viewTemplate)
    {
        $this->viewTemplate = $viewTemplate;
        return $this;
    }
}
