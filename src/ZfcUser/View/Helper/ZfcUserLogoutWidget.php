<?php

namespace ZfcUser\View\Helper;

use Zend\View\Helper\AbstractHelper;
use ZfcUser\Form\Logout as LogoutForm;
use Zend\View\Model\ViewModel;

class ZfcUserLogoutWidget extends AbstractHelper
{
    /**
     * Logout Form
     * @var LogoutForm
     */
    protected $logoutForm;

    /**
     * __invoke
     *
     * @access public
     * @param array $options array of options
     * @return string
     */
    public function __invoke($options = array())
    {
        $render = (array_key_exists('render', $options)) ? $options['render'] : true;

        $vm = new ViewModel(array(
            'logoutForm' => $this->getLogoutForm(),
        ));
        $vm->setTemplate('zfc-user/user/logoutform');

        return ($render) ? $this->getView()->render($vm) : $vm;
    }

    /**
     * Retrieve Logout Form Object
     * @return LogoutForm
     */
    public function getLogoutForm()
    {
        return $this->logoutForm;
    }

    /**
     * Inject Logout Form Object
     * @param LogoutForm $logoutForm
     * @return ZfcUserLogoutWidget
     */
    public function setLogoutForm(LogoutForm $logoutForm)
    {
        $this->logoutForm = $logoutForm;
        return $this;
    }
}
