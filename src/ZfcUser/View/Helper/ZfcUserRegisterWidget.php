<?php

namespace ZfcUser\View\Helper;

use Zend\View\Helper\AbstractHelper;
use ZfcUser\Form\Register as RegisterForm;
use Zend\View\Model\ViewModel;

class ZfcUserRegisterWidget extends AbstractHelper
{
    /**
     * Register Form
     * @var RegisterForm
     */
    protected $registerForm;

    /**
     * $var string template used for view
     */
    protected $viewTemplate;
    
    
     /**
     * @var bool
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
            'registerForm' => $this->getRegisterForm(),
        	'enableRegistration' => $this->getEnableRegistration(),
            'redirect'  => $redirect,
        ));
        $vm->setTemplate($this->viewTemplate);
        if ($render) {
            return $this->getView()->render($vm);
        } else {
            return $vm;
        }
    }

    /**
     * Retrieve Register Form Object
     * @return RegisterForm
     */
    public function getRegisterForm()
    {
        return $this->registerForm;
    }

    /**
     * Inject Register Form Object
     * @param RegisterForm $registerForm
     * @return ZfcUserRegisterWidget
     */
    public function setRegisterForm(RegisterForm $registerForm)
    {
        $this->registerForm = $registerForm;
        return $this;
    }

    /**
     * @param string $viewTemplate
     * @return ZfcUserRegisterWidget
     */
    public function setViewTemplate($viewTemplate)
    {
        $this->viewTemplate = $viewTemplate;
        return $this;
    }
    
    
    /**
     * set enable user registration
     *
     * @param bool $enableRegistration
     * @return EnableRegistrationModuleOption
     */
    public function setEnableRegistration($enableRegistration)
    {
    	$this->enableRegistration = $enableRegistration;
    	return $this;
    }
    
    /**
     * get enable user registration
     *
     * @return bool
     */
    public function getEnableRegistration()
    {
    	return $this->enableRegistration;
    }

}
