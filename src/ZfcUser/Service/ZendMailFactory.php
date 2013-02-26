<?php

namespace ZfcUser\Service;

use Zend\Mail\Transport;
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
     * Return the transport to use for sending mail.
     *
     * @param MailOptions $options
     * @return Transport\TransportInterface
     */
    protected function createTransport(MailOptions $options)
    {
        $transportOptions = $options->getTransportOptions();

        $type = $transportOptions['type'];

        switch ($type) {
            case 'sendmail':
                $transport = new Transport\Sendmail();
                break;
            case 'smtp':
                $transport = new Transport\Smtp();
                $smtpOptions = new Transport\SmtpOptions($transportOptions['smtp_options']);
                $transport->setOptions($smtpOptions);
                break;
            default:
                throw new Exception\InvalidArgumentException(
                    "Mail transport type can be 'sendmail' or 'smtp'; got '$type'"
                );
        }

        return $transport;
    }

    /**
     * Create an instance of {@see ZendMail}
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return ZendMail
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = $serviceLocator->get('zfcuser_module_options');
        $mailOptions = $options->getMail();

        return new ZendMail(
            $this->createRenderer($mailOptions),
            $this->createTransport($mailOptions)
        );
    }
}
