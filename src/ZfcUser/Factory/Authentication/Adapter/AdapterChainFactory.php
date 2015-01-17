<?php
namespace ZfcUser\Factory\Authentication\Adapter;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcUser\Authentication\Adapter\AdapterChain;

class AdapterChainFactory implements FactoryInterface
{
    /**
     * @var ModuleOptions
     */
    protected $options;

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = $serviceLocator->get('zfcuser_module_options');
        
        $chain = new AdapterChain();
        foreach ($options->getAuthAdapters() as $priority => $adapterName) {
            $chain->attach($adapterName, $serviceLocator->get($adapterName), $priority);
        }

        return $chain;
    }
}
