<?php

/*
 *
 *
 * @copyright Copyright (c) 2013-2014 KipsProduction (http://www.kips.gr.jp)
 * @license   http://www.kips.gr.jp/newbsd/LICENSE.txt New BSD License
 */
namespace NpDocument\Model\Section;

use Flower\Model\AbstractEntity;
use NpDocument\Exception\DomainException;
use NpDocument\Model\Document\Document;
/**
 * データベースとのデータ交換キャリア
 * エンティティ内でデータをデータベース内部表現で保持
 */
class DataContainer extends AbstractEntity
{
    protected $authenticated = false;
    
    protected $identifier = array('domain_id', 'document_id', 'section_name', 'section_rev');
    
    protected $immutableColumns = array(
        'global_section_id',
        'global_document_id',
        'domain_id',
        'document_id',
        'section_name',
        'section_rev',
    );
    
    protected $useSetterGetterColumns = array(
        'section_properties',
        
    );
    
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
    
    public function setIdentifier(array $identifier)
    {
        $this->identifier = $identifier;
    }
    
    public function getIdentifier()
    {
        return $this->identifier;
    }
    
    /**
     * 
     * @param integer $domainId
     * @param integer $documentId
     */
    public static function generateGlobalSectionId($domainId, $documentId, $sectionName, $sectionRev)
    {
        $globalDocumentId = Document::generateGlobalDocumentId($domainId, $documentId);
        
        /**
         * @todo use Validator with DI
         */
        if (!is_string($sectionName)) {
            throw new DomainException('section_name should be string');
        }
        
        if (!is_int($sectionRev)) {
            throw new DomainException('section_rev should be int');
        }
        
        switch (true) {
            case empty($sectionName):
                throw new DomainException('section_name should not be empty');
            case (strlen($sectionName) > 32):
                throw new DomainException('section_name too long');
            case (preg_match('/[^a-zA-Z0-9-_]/', $sectionName)):
                throw new DomainException('The input contains characters which are non alphabetic and no digits');
            case ($sectionRev > 9999):
                throw new DomainException('rev is too large');
        }
        
        return sprintf($globalDocumentId . Document::GLOBAL_DOCUMENT_DELIMITER . 
                '%s' . SectionInterface::SECTION_REV_DELIMITER . '%u', $sectionName, $sectionRev);
        
    }
    
    public function setOriginate($domainId, $documentId, $sectionName, $sectionRev, $force = false)
    {
        $this->privateOffsetSet('domain_id', $domainId, $force);
        $this->privateOffsetSet('document_id', $documentId, $force);
        $this->privateOffsetSet('section_name', $sectionName, $force);
        $this->privateOffsetSet('section_rev', $sectionRev, $force);
        
    }
    
    /**
     * 
     * @param string $name
     * @param mixed $value
     * @return void
     */
    private function privateOffsetSet($name, $value, $force = false)
    {
        if (isset($this->columns[$name])) {
            if (!$force 
                && false !== array_search($name, $this->immutableColumns)
                && $this->offsetIsset($name)) {
                throw new DomainException('specified column is immutable. Don\'t overwrite it. make new container with new id');
            }
            return parent::offsetSet($name, $value);
        }
    }

    /**
     * データコンテナはデータベースcolumn以外のキーを暗黙的にセットしない
     * エンティティを特定するcolumnは暗黙的な上書きは不可
     * 
     * @param string $name
     * @param mixed $value
     * @return void
     * @throws NpDocument\Exception\DomainException
     */
    public function offsetSet($name, $value)
    {
        if (!isset($this->columns[$name])) {
            throw new DomainException('specified name ' . $name .' is not in columns');
        }
        
        if (false !== array_search($name, $this->immutableColumns)
                && $this->offsetIsset($name)) {
            throw new DomainException('specified column is immutable. Don\'t overwrite it. make new container with new id');
        }
        
        return parent::offsetSet($name, $value);
    }
}