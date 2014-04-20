<?php
namespace ZfcUser\View\Helper;

/**
 * Class LoginFormHelper
 * @package ZfcUser\View\Helper
 */
class LoginFormHelper extends AbstractFormHelper
{
    /**
     * @var string
     */
    protected $template = 'zfc-user/user/login';

    /**
     * @param  array    $options array of options
     * @return string
     */
    public function __invoke($options = array())
    {
        $viewModel = parent::__invoke($options);
        $viewModel->setVariables([
            'loginForm' => $this->form,
            'redirect'  => $this->redirect,
        ]);

        if ($this->render) {
            return $this->getView()->render($viewModel);
        }

        return $viewModel;
    }
}
