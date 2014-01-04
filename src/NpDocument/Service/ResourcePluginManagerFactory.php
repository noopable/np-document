<?php
namespace NpDocument\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\Config as ServiceManagerConfig;
use NpDocument\Resource\ResourcePluginManager;
/**
 * Description of BlockPluginManagerFactory
 *
 * @author tomoaki
 */
class ResourcePluginManagerFactory implements FactoryInterface {
    
    protected $configId = 'np_document_resources';
    /**
     * Create and return abstract factory seeded by dependency injector
     *
     * Creates and returns an abstract factory seeded by the dependency
     * injector. If the "di" key of the configuration service is set, that
     * sub-array is passed to a DiConfig object and used to configure
     * the DI instance. The DI instance is then used to seed the
     * DiAbstractServiceFactory, which is then registered with the service
     * manager.
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Di
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config')[$this->configId];
        
        $oConfig = new ServiceManagerConfig($config);
        $plugin = new ResourcePluginManager($oConfig);

        return $plugin;
    }
}
