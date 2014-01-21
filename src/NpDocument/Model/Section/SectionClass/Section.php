<?php

/*
 *
 *
 * @copyright Copyright (c) 2013-2014 KipsProduction (http://www.kips.gr.jp)
 * @license   http://www.kips.gr.jp/newbsd/LICENSE.txt New BSD License
 */
namespace NpDocument\Model\Section\SectionClass;

use Flower\Model\AbstractEntity;
use NpDocument\Model\Section\Config;
use NpDocument\Model\Section\DataContainer;
use NpDocument\Model\Section\SectionInterface;

class Section implements SectionInterface
{
    
    /**
     *
     * @var Flower\Model\AbstractEntity
     */
    protected $dataContainer;
    
    public function __construct(Config $config = null)
    {
        if (null !== $config) {
            $config->configure($this);
        }
    }
    
    /**
     * 
     * @return ArrayObject
     */
    public function getDataContainer()
    {
        if (!isset($this->dataContainer)) {
            $this->dataContainer = new DataContainer;
        }
        return $this->dataContainer;
    }

    public function setDataContainer(AbstractEntity $dataContainer)
    {
        $this->dataContainer = $dataContainer;
    }
    
    public function __set($name, $value)
    {
        return $this->getDataContainer()->offsetSet($name, $value);
    }
    
    public function __get($name)
    {
        return $this->getDataContainer()->offsetGet($name);
    }
    
    public function __isset($name)
    {
        return $this->getDataContainer()->offsetExists($name);
    }

}