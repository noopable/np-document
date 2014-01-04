<?php

/*
 * Here comes the text of your license
 * Each line should be prefixed with  * 
 */

namespace NpDocument\Resource\Manager;

use NpDocument\Resource\Converter\DefaultConverter;
use NpDocument\Resource\ResourcePluginManager;
/**
 * Description of Config
 *
 * @author tomoaki
 */
class Config {
    protected $config;
    
    protected $resourcePluginManagerServiceName = 'NpDocument_Resources';
    public function __construct(array $config)
    {
        $this->config = $config;
    }
    
    public function configure(ManagerInterface $manager)
    {
        if (isset($this->config['service_locator'])) {
            $serviceLocator = $this->config['service_locator'];
        }
        
        if (isset($this->config['cache_storage'])) {
            $traits = class_uses($manager);
            if (in_array('NpDocument\Resource\Manager\CacheStorageTrait', $traits)) {
                $storage = \Zend\Cache\StorageFactory::factory($this->config['cache_storage']);
                $manager->setStorage($storage);
            }
        }
        
        if (isset($this->config['converter'])) {
            $converter = new $this->config['converter'];
        } else {
            $converter = new DefaultConverter;
        }
        
        $manager->setConverter($converter);
        
        if (isset($serviceLocator) && isset($converter) && method_exists($converter, 'setResourcePluginManager')) {
            //2階層以上のカスタマイズになると、DIが優位
            $resourcePluginService = isset($this->config['resource_plugin_manager'])
                                                ? $this->config['resource_plugin_manager']
                                                : $this->resourcePluginManagerServiceName;
            if ($serviceLocator->has($resourcePluginService)) {
                $resourcePluginManager = $serviceLocator->get($resourcePluginService);
                if ($resourcePluginManager instanceof ResourcePluginManager) {
                    $converter->setResourcePluginManager($resourcePluginManager);
                }
            }
        }

        if (isset($this->config['mapper'])) {
            
        }
    }
}
