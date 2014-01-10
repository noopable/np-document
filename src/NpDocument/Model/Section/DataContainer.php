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
    
    protected $columns = array (
        'global_section_id' => 'global_section_id',
        'global_document_id' => 'global_document_id',
        'domain_id' => 'domain_id',
        'document_id' => 'document_id',
        'section_class' => 'section_class',
        'section_name' => 'section_name',
        'section_rev' => 'section_rev',
        'branch_set' => 'branch_set',
        'release_tag' => 'release_tag',
        'content' => 'content',
        'section_properties' => 'section_properties',
        'editor_id' => 'editor_id',
        'capture_to' => 'capture_to',
        'kvs_resource_id' => 'kvs_resource_id',
        'kvs_resource_class' => 'kvs_resource_class',
        'acl_resource_id' => 'acl_resource_id',
        'status' => 'status',
        'priority' => 'priority',
        'lastupdated' => 'lastupdated',
        'template_name' => 'template_name',
        'section_to_string' => 'section_to_string',
        'section_note' => 'section_note',
    );
    
    public function setIdentifier(array $ids)
    {
        $this->identifier = $ids;
    }
    
    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function offsetSet($name, $value)
    {
        if (isset($this->columns[$name])) {
            return parent::offsetSet($name, $value);
        }
    }
}