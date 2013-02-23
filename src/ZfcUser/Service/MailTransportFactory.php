<?php

namespace ZfcUser\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Mail\MailTransportInterface;
use ZfcUser\Service\Exception\InvalidArgumentException;

/**
 * Factory for creating an instance of the selected mail transport.
 *
 * @author Tom Oram <tom@x2k.co.uk>
 */
class MailTransportFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param string                  $transportClass
     * @return MessagerTransportInterface
     */
    protected function createTransportInstances(ServiceLocatorInterface $serviceLocator, $transportClass)
    {
        if ($serviceLocator->has($transportClass)) {
            return $serviceLocator->get($transportClass);
        }

        if (class_exists($transportClass)) {
            return new $transportClass;
        }

        return null;
    }

    /**
     * Creates an instance of the message fetcher.
     *
     * @return MessageTransportInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = $serviceLocator->get('zfcuser_module_options');
        $mailOptions = $options->getMail();

        $transportClass = $mailOptions->getTransportClass();

        $transport = $this->createTransportInstances($serviceLocator, $transportClass);

        if (!$transport instanceof MailTransportInterface) {
            throw new InvalidArgumentException(
                sprintf(
                    'Message fetcher must be an instance of %s; got "%s"',
                    '\ZfcUser\Mail\MailTransportInterface',
                    is_object($transport) ? get_class($transport) : gettype($transport)
                )
            );
        }

        return $transport;
    }
}
