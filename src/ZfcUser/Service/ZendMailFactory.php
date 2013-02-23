<?php

namespace ZfcUser\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\AggregateResolver;
use Zend\View\Resolver\TemplatePathStack;
use ZfcUser\Mail\ZendMail;
use ZfcUser\Options\MailOptions;

/**
 * Factory for creating an instance of {@see ZendMail}
 *
 * @author Tom Oram <tom@x2k.co.uk>
 */
class ZendMailFactory implements FactoryInterface
{
    /**
     * Returns an instance of {@see TemplatePathStack}
     *
     * @param array|string $paths
     * @return TemplatePathStack
     */
    protected function createTemplatePathStack($paths)
    {
        if (is_array($paths)) {
            return  new TemplatePathStack(array('script_paths' => $paths));
        }

        return  new TemplatePathStack(array('script_paths' => array($paths)));
    }

    /**
     * Creats an instance of {@see PhpRenderer}
     *
     * @param MailOptions $options
     * @return PhpRenderer
     */
    protected function createRenderer(MailOptions $options)
    {
        $renderer = new PhpRenderer();

        $resolver = new AggregateResolver();

        $renderer->setResolver($resolver);

        $fetcherOptions = $options->getMessageFetcherOptions();

        $stack = $this->createTemplatePathStack($fetcherOptions['template_paths']);

        $resolver->attach($stack);

        return $renderer;
    }

    /**
     * Create an instance of {@see ZendMail}
     *
     * @return ZendMail
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = $serviceLocator->get('zfcuser_module_options');
        $mailOptions = $options->getMail();

        $renderer = $this->createRenderer($mailOptions);

        return new ZendMail($renderer);
    }
}
