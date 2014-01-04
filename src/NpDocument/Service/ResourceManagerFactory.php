<?php
namespace NpDocument\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use NpDocument\Resource\Manager\Config;
use NpDocument\Resource\Manager\ManagerInterface;
use NpDocument\Exception\RuntimeException;

/**
 * Description of BlockPluginManagerFactory
 *
 * @author tomoaki
 */
class ResourceManagerFactory implements FactoryInterface {
    
    protected $configId = 'np_document_resource_manager';
    /**
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return NpDocument\Resource\Manager\ManagerInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config')[$this->configId];
        
        if (isset($config['di'])) {
            $di = new \Zend\Di\Di;
            $diConfig =new \Zend\Di\Config($config['di']);
            $diConfig->configure($di);
            try {
                //alias get
                $service = $di->get('manager');
            } catch (\Zend\Di\Exception\ExceptionInterface $e) {
                if (!isset($config['throw_exception']) || $config['throw_exception'] !== false) {
                    throw new RuntimeException('invalid configuration of resource manager of di', 0, $e);
                }
            }
        } 
        
        if (!isset($service) || ! $service instanceof ManagerInterface) {
            $class = isset($config['class']) ? $config['class'] : 'NpDocument\Resource\Manager\StandardManager';
            $config['service_locator'] = $serviceLocator;
            //一定の複雑さやバリエーションが生じる可能性があるなら、DIによる構成を検討するべき。
            //ここでは限定的に機能を構成する？
            $managerConfig = new Config($config);
            $service = new $class;
            $managerConfig->configure($service);
        }

        return $service;
    }
}
