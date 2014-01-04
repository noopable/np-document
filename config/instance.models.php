<?php
return array(
    'alias' => array( //add alias first !
        //リソースfactoryでブリッジ指定したサービスは、Aliasを張ることでDIで使用可能になる。
        //このalias指定がないと、パラメーターとして指定したときインスタンスと解釈してくれない。
        'dbAdapter' => 'Zend\Db\Adapter\Adapter',
        'ItemTable' => 'Zend\Db\TableGateway\TableGateway',
        'ClientTable' =>   'Zend\Db\TableGateway\TableGateway',
        'SandboxTable' =>   'Zend\Db\TableGateway\TableGateway',
    ),
    'preferences' => array(
    ),
    'SandboxTable' => array(
        'parameters' => array(
            'table' => 'sandbox',
            'adapter' => 'dbAdapter',
        ),
    ),
    'NpDocument\Model\Repository\Sandbox' => array(
        'parameters' => array(
            'name' => 'sandbox',
            'entityPrototype' => 'NpDocument\Model\Sandbox\Sandbox',
            'tableGateway' => 'SandboxTable',
        ),
    ),
    'ClientTable' => array(
        'parameters' => array(
            'table' => 'client',
            'adapter' => 'dbAdapter',
        ),
    ),
    //'Zend\InputFilter\InputFilter' => array(),
    'NpDocument\Model\Client\Client' => array(
        'parameters' => array(
            'array' => array(),
        ),
    ),
    'NpDocument\Model\Repository\ClientDb' => array(
        'parameters' => array(
            'name' => 'client',
            'entityPrototype' => 'NpDocument\Model\Client\Client',
            'tableGateway' => 'ClientTable',
        ),
    ),
    'NpDocument\Model\Repository\ClientSession' => array(
        'parameters' => array(
            'name' => 'cart',
            'entityPrototype' => 'NpDocument\Model\Client\Client',
            'namespace' => 'NpDocument\Client',
        ),
    ),
);