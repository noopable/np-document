<?php

/*
 *
 * @copyright Copyright (c) 2013-2014 KipsProduction (http://www.kips.gr.jp)
 * @license   http://www.kips.gr.jp/newbsd/LICENSE.txt New BSD License
 */

namespace NpDocument\Model\Document\Service;

/**
 * @deprecated 通常、ドメインオブジェクトからサービスロケータを使って外部に直接アクセスことはないよって、このクラスは使わない。
 * ただし、delegateできるクラスとしてしばらく残す
 *
 * @author Tomoaki Kosugi <kosugi at kips.gr.jp>
 */
class ResourceManager {

    protected $resourceManager;

    protected $resourceManagerName = 'Document_Resource_Manager';

    protected $document;

    protected $name;
    /**
     * Constructor
     *
     * @param ConfigInterface $config
     */
    public function __construct($document, $name = null)
    {
        if (null !== $name) {
            $this->name = $name;
        }
        $this->document = $document;
        $this->resourceManager = $document->serviceManager_get($this->resourceManagerName);
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array(array($this->resourceManager, $name), $arguments);
    }
}
