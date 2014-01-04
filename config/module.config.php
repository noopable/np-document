<?php
return array(
    'service_manager' => array( 
        //Module.phpの getServiceConfigでも実装できる。ハードコートしたくなければこちらで。
        'factories' => array(
            'NpDocument_Repositories'
                => 'NpDocument\Service\RepositoryServiceFactory',
            'NpDocument_Resource_Manager'
                => 'NpDocument\Service\ResourceManagerFactory',
            'NpDocument_Resources'
                => 'NpDocument\Service\ResourcePluginManagerFactory',
        ),
        'shared' => array(
        ),
    ),
    'di' => array(
        //'definition' => include __DIR__ . '/definition.php',
        'instance' => include __DIR__ . '/instance.models.php',
    ),
    'np_document_resources' => array(
        'invokables' => array(
            'generic' => 'NpDocument\Resource\ResourceClass\Resource',
        ),
    ),
    'np_document_resource_manager' => array(
        'resource_plugin_manager' => 'NpDocument_Resources',
        //reserve configuration 
        /**
         * class => マネージャークラスを換装できる
         * その他はResource\Manager\Config経由でconfigureされるオプション
         * 
         */
        //@see http://framework.zend.com/manual/2.2/en/modules/zend.cache.storage.adapter.html
        'cache_storage' => array(
            'adapter' => array(
                'name'    => 'filesystem',
                'options' => array(
                    'namespace' => 'zfcache_resource_manager',
                    'cache_dir' => dirname(__DIR__) . '/data/resource',
                    'dir_level' => 2,
                ),
            ),
            'plugins' => array(
                'exception_handler' => array('throw_exceptions' => true),
            ),
        ),
    ),
);
