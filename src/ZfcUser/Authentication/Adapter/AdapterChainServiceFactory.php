<?php
namespace ZfcUser\Authentication\Adapter;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Authentication\Adapter\AdapterChain;
use ZfcUser\Options\ModuleOptions;
use ZfcUser\Authentication\Adapter\Exception\OptionsNotFoundException;

class AdapterChainServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceLocator, $requestedName, array $options = null)
    {
        $chain = new AdapterChain();

        $options = $this->getOptions($serviceLocator);

        //iterate and attach multiple adapters and events if offered
        foreach ($options->getAuthAdapters() as $priority => $adapterName) {
            $adapter = $serviceLocator->get($adapterName);

            if (is_callable(array($adapter, 'authenticate'))) {
                $chain->getEventManager()->attach('authenticate', array($adapter, 'authenticate'), $priority);
            }

            if (is_callable(array($adapter, 'logout'))) {
                $chain->getEventManager()->attach('logout', array($adapter, 'logout'), $priority);
            }
        }

        return $chain;
    }

    /**
     * @var ModuleOptions
     */
    protected $options;

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->__invoke($serviceLocator, null);
    }


    /**
     * set options
     *
     * @param ModuleOptions $options
     * @return AdapterChainServiceFactory
     */
    public function setOptions(ModuleOptions $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * get options
     *
     * @param ServiceLocatorInterface $serviceLocator (optional) Service Locator
     * @return ModuleOptions $options
     * @throws OptionsNotFoundException If options tried to retrieve without being set but no SL was provided
     */
    public function getOptions(ServiceLocatorInterface $serviceLocator = null)
    {
        if (!$this->options) {
            if (!$serviceLocator) {
                throw new OptionsNotFoundException(
                    'Options were tried to retrieve but not set ' .
                    'and no service locator was provided'
                );
            }

            $this->setOptions($serviceLocator->get('zfcuser_module_options'));
        }

        return $this->options;
    }
}
