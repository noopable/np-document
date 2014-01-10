<?php

/*
 *
 *
 * @copyright Copyright (c) 2013-2014 KipsProduction (http://www.kips.gr.jp)
 * @license   http://www.kips.gr.jp/newbsd/LICENSE.txt New BSD License
 */
namespace NpDocument\Model\Section\SectionClass;

use Flower\Model\AbstractEntity;
use NpDocument\Model\Section\DataContainer;
use NpDocument\Model\Section\SectionInterface;

class Section extends AbstractEntity implements SectionInterface
{
    protected $authenticated = false;
    
    /**
     *
     * @var Flower\Model\AbstractEntity
     */
    protected $dataContainer;

    public function getIdentifier()
    {
        return array('domain_id', 'document_id', 'section_name', 'section_rev');
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
    
    public function offsetSet($name, $value)
    {
        return $this->getDataContainer()->offsetSet($name, $value);
    }
    
    public function offsetGet($name)
    {
        return $this->getDataContainer()->offsetGet($name);
    }
    
    public function offsetExists($name)
    {
        return $this->getDataContainer()->offsetExists($name);
    }
}