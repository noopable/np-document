<?php

/*
 *
 * @copyright Copyright (c) 2013-2014 KipsProduction (http://www.kips.gr.jp)
 * @license   http://www.kips.gr.jp/newbsd/LICENSE.txt New BSD License
 */

namespace NpDocument\Model\Document\Service;

use Zend\ServiceManager\ServiceManager as ZfServiceManager;

/**
 *
 * @deprecated 通常、ドメインオブジェクトからサービスロケータを使って外部に直接アクセスことはないよって、このクラスは使わない。
 * ただし、delegateできるクラスとしてしばらく残す
 * 
 * @author Tomoaki Kosugi <kosugi at kips.gr.jp>
 */
class ServiceManager extends ZfServiceManager {

    protected $serviceManager;

    protected $document;

    protected $name;
    /**
     * Constructor
     *
     * @param ConfigInterface $config
     */
    public function __construct($document, $name = null)
    {
        parent::__construct();
        if (null !== $name) {
            $this->name = $name;
        }
        $this->document = $document;
        $document->setService($this->name, $this);
    }

    public function addParentServiceManager(ServiceManager $serviceManager)
    {
        $this->addPeeringServiceManager($serviceManager);
    }
}
