<?php
return array(
    'service_manager' => array( 
        //Module.phpの getServiceConfigでも実装できる。ハードコートしたくなければこちらで。
        'factories' => array(
            'NpDocument_Repositories'
                => 'NpDocument\Service\RepositoryServiceFactory',
        ),
        'shared' => array(
        ),
    ),
    'di' => array(
        //'definition' => include __DIR__ . '/definition.php',
        'instance' => include __DIR__ . '/instance.models.php',
    ),
);
