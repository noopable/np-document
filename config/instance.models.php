<?php
return array(
    'alias' => array( //add alias first !
        //リソースfactoryでブリッジ指定したサービスは、Aliasを張ることでDIで使用可能になる。
        //このalias指定がないと、パラメーターとして指定したときインスタンスと解釈してくれない。
        'dbAdapter' => 'Zend\Db\Adapter\Adapter',
        'DocumentLinkTable' => 'Zend\Db\TableGateway\TableGateway',
        'DocumentTable' =>   'NpDocument\Model\Document\DocumentTableGateway',
        'SectionTable' => 'Zend\Db\TableGateway\TableGateway',
        'SandboxTable' =>   'Zend\Db\TableGateway\TableGateway',
    ),
    'preferences' => array(
    ),
    'DocumentTable' => array(
        'parameters' => array(
            'table' => 'document',
            'adapter' => 'dbAdapter',
        ),
    ),
    'NpDocument\Model\Document\DocumentClass\Document' => array(
        'parameters' => array(
            'array' => array(),
        ),
    ),
    'NpDocument\Model\Repository\Document' => array(
        'parameters' => array(
            'name' => 'document',
            'entityPrototype' => 'NpDocument\Model\Document\DocumentClass\Document',
            'tableGateway' => 'DocumentTable',
        ),
        'injections' => array(
            'setSectionRepository' => array(
                array('NpDocument\Model\Repository\Section'),
            ),
            'setDocumentLinkRepository' => array(
                array('NpDocument\Model\Repository\DocumentLink'),
            ),
        ),
    ),
    'DocumentLinkTable' => array(
        'parameters' => array(
            'table' => 'document_link',
            'adapter' => 'dbAdapter',
        ),
    ),
    'NpDocument\Model\DocumentLink' => array(
        'parameters' => array(
            'array' => array(),
        ),
    ),
    'NpDocument\Model\Repository\DocumentLink' => array(
        'parameters' => array(
            'name' => 'document',
            'entityPrototype' => 'NpDocument\Model\DocumentLink',
            'tableGateway' => 'DocumentLinkTable',
            'options' => array(
                'select' => array(
                    'order' => array(
                        'priority' => 'DESC',
                    ),
                ),
            ),
        ),
    ),
    'SectionTable' => array(
        'parameters' => array(
            'table' => 'section',
            'adapter' => 'dbAdapter',
        ),
    ),
    'NpDocument\Model\Section\DataContainer' => array(
        'parameters' => array(
            'array' => array(),
        ),
    ),
    'NpDocument\Model\Repository\Section' => array(
        'parameters' => array(
            'name' => 'section',
            'entityPrototype' => 'NpDocument\Model\Section\DataContainer',
            'tableGateway' => 'SectionTable',
            'sectionPluginManger' => 'NpDocument\Model\Section\SectionPluginManager',
        ),
    ),
    'NpDocument\Model\Section\SectionPluginManager' => array(
        'injections' => array(
            'setInvokableClass' => array(
                array('name' => 'generic', 'invokableClass' => 'NpDocument\Model\Section\SectionClass\Section'),
            ),
        ),
    ),
    'NpDocument\Model\Sandbox\Sandbox' => array(
        'parameters' => array(
            'array' => array(),
        ),
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
);