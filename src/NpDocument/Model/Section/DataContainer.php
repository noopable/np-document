<?php

/*
 *
 *
 * @copyright Copyright (c) 2013-2014 KipsProduction (http://www.kips.gr.jp)
 * @license   http://www.kips.gr.jp/newbsd/LICENSE.txt New BSD License
 */
namespace NpDocument\Model\Section;

use Flower\Model\AbstractEntity;

class DataContainer extends AbstractEntity
{
    protected $authenticated = false;
    
    protected $identifier = array('domain_id', 'document_id', 'section_name', 'section_rev');
    
    public function setIdentifier(array $ids)
    {
        $this->identifier = $ids;
    }
    
    public function getIdentifier()
    {
        return $this->identifier;
    }

}