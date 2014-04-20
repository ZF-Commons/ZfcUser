<?php
namespace ZfcUser\View\Helper;

use Zend\Form\FormInterface;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

/**
 * Class AbstractFormHelper
 * @package ZfcUser\View\Helper
 */
abstract class AbstractFormHelper extends AbstractHelper
{
    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * @var bool
     */
    protected $redirect = false;

    /**
     * @var bool
     */
    protected $render = true;

    /**
     * $var string
     */
    protected $template;

    /**
     * @param FormInterface $form
     * @param $template
     */
    public function __construct(FormInterface $form, $template = null)
    {
        $this->form     = $form;

        if ($template) {
            $this->template = $template;
        }
    }

    /**
     * @param  array    $options array of options
     * @return ViewModel
     */
    public function __invoke($options = array())
    {
        if (array_key_exists('redirect', $options)) {
            $this->redirect = $options['redirect'];
        }

        if (array_key_exists('render', $options)) {
            $this->render = $options['render'];
        }

        $viewModel = new ViewModel();
        $viewModel->setTemplate($this->template);

        return $viewModel;
    }

    /**
     * Use a different template
     *
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }
}
