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
     * RegistrationOptions
     * @var registrationOptions
     */
    protected $enableRegistration;
    
    /**
     * RegistrationOptions
     * @var registrationOptions
     */
    protected $enableRegistration;
    
    /**
     * __invoke
     *
     * @access public
     * @param array $options array of options
     * @return string
     */
    public function __invoke($options = array())
    {
        if (array_key_exists('render', $options)) {
            $render = $options['render'];
        } else {
            $render = true;
        }
        if (array_key_exists('redirect', $options)) {
            $redirect = $options['redirect'];
        } else {
            $redirect = false;
        }

        $vm = new ViewModel(array(
            'loginForm' => $this->getLoginForm(),
            'redirect'  => $redirect,
            'enableRegistration' => $this->registrationOptions->getEnableRegistration()
        ));
        $vm->setTemplate($this->viewTemplate);
        if ($render) {
            return $this->getView()->render($vm);
        } else {
            return $vm;
        }
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

    /**
     * Retrieve module options
     * @param $options
     * @return ZfcUserLoginWidget
     */
    public function getRegistrationOptions()
    {
        return $this->enableRegistration;
    }

    /**
     * Inject module options
     * @param bool $registrationOption
     * @return ZfcUserLoginWidget
     */
    public function setRegistrationOptions($registrationOption)
    {
        $this->enableRegistration = $registrationOption;
        return $this;
    }
}
