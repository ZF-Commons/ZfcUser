<?php

namespace ZfcUser\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Mail\MessageFetcherInterface;
use ZfcUser\Service\Exception\InvalidArgumentException;

/**
 * Factory for creating an instance of the selected message fetcher.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class MessageFetcherFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param string                  $fetcherClass
     * @return MessageFetcherInterface
     */
    protected function createFetcherInstances(ServiceLocatorInterface $serviceLocator, $fetcherClass)
    {
        if ($serviceLocator->has($fetcherClass)) {
            return $serviceLocator->get($fetcherClass);
        }

        if (class_exists($fetcherClass)) {
            return new $fetcherClass;
        }

        return null;
    }

    /**
     * Creates an instance of the message fetcher.
     *
     * @return MessageFetcherInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = $serviceLocator->get('zfcuser_module_options');
        $mailOptions = $options->getMail();

        $fetcherClass = $mailOptions->getMessageFetcherClass();

        $fetcher = $this->createFetcherInstances($serviceLocator, $fetcherClass);

        if (!$fetcher instanceof MessageFetcherInterface) {
            throw new InvalidArgumentException(
                sprintf(
                    'Message fetcher must be an instance of %s; got "%s"',
                    '\ZfcUser\Mail\MessageFetcherInterface',
                    is_object($fetcher) ? get_class($fetcher) : gettype($fetcher)
                )
            );
        }

        return $fetcher;
    }
}
