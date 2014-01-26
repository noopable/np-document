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
use NpDocument\Model\Document\AbstractDocument;
use NpDocument\Model\Document\DocumentInterface;
use Zend\Config\Exception\RuntimeException as ConfigRuntimeException;
use Zend\Config\Writer\Xml as ConfigWriter;
use Zend\Config\Reader\Xml as ConfigReader;
/**
 * データベースとのデータ交換キャリア
 * エンティティ内でデータをデータベース内部表現で保持
 */
class DataContainer extends AbstractEntity
{

    protected $identifier = array('domain_id', 'document_id', 'section_name', 'section_rev');

    protected $properties = array();

    protected $status = array();

    protected $priority = array();

    protected $priorityStrings = array(
        '9','8','7','6','5','4','3','2','1','low','medium-low','medium','medium-hight','high','hidden_bottom','fixed_bottom','footer','content_post','content','content_pre','header','fixed_top','hidden_top'
    );

    protected $branchSet = array();

    protected $xmlConfigReader;

    protected $xmlConfigWriter;

    protected $immutableColumns = array(
        'global_section_id',
        'global_document_id',
        'domain_id',
        'document_id',
        'section_name',
        'section_rev',
    );

    protected $setterGetterColumns = array(
        'section_properties',
        'status',
        'priority',
        'branch_set',
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
        $globalDocumentId = AbstractDocument::generateGlobalDocumentId($domainId, $documentId);

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

        return sprintf($globalDocumentId . DocumentInterface::GLOBAL_DOCUMENT_DELIMITER .
                '%s' . SectionInterface::SECTION_REV_DELIMITER . '%u', $sectionName, $sectionRev);

    }

    public function setXmlConfigReader(ConfigReader $xmlConfigReader)
    {
        $this->xmlConfigReader = $xmlConfigReader;
    }

    public function getXmlConfigReader()
    {
        if (!isset($this->xmlConfigReader)) {
            $this->xmlConfigReader = new ConfigReader;
        }
        return $this->xmlConfigReader;
    }

    public function setXmlConfigWriter(ConfigWriter $xmlConfigWriter)
    {
        $this->xmlConfigWriter = $xmlConfigWriter;
    }

    public function getXmlConfigWriter()
    {
        if (!isset($this->xmlConfigWriter)) {
            $this->xmlConfigWriter = new ConfigWriter;
        }
        return $this->xmlConfigWriter;
    }

    public function setProperty($name, $value)
    {
        $this->properties[$name] = $value;
    }

    public function getProperty($name)
    {
        if (isset($this->properties[$name])) {
            return $this->properties[$name];
        }
    }

    public function issetProperty($name)
    {
        return isset($this->properties[$name]);
    }
    /**
     *
     * @param string $properties XML
     */
    public function setSectionProperties($xmlString)
    {
        try {
            $parsed = $this->getXmlConfigReader()->fromString($xmlString);
            if (is_array($parsed)) {
                $this->properties = $parsed;
            } else {
                throw new DomainException('invalid XML string of section_property(2)');
            }
        } catch (ConfigRuntimeException $ex) {
            throw new DomainException('invalid XML string of section_property(1)', 0, $ex);
        }
        parent::offsetSet('section_properties', $xmlString);
    }

    /**
     * @return string XML
     */
    public function getSectionProperties()
    {
        $xmlString = $this->getXmlConfigWriter()->processConfig($this->properties);
        parent::offsetSet('section_properties', $xmlString);
        return $xmlString;
    }

    public function setStatusFlag($state, $flag = true)
    {
        if ($flag) {
            $this->status[$state] = $state;
        } else {
            if (isset($this->status[$state])) {
                unset($this->status[$state]);
            }
        }
    }

    public function getStatusFlag($state)
    {
        return isset($this->status[$state]);
    }

    public function setStatus($commaSeparated)
    {
        $setArray = array_flip(array_flip(explode(',', $commaSeparated)));
        $this->status = array_combine($setArray, $setArray);
        parent::offsetSet('status', $commaSeparated);
    }

    public function getStatus()
    {
        $commaSeparated = implode(',', $this->status);
        parent::offsetSet('status', $commaSeparated);
        return $commaSeparated;
    }

    /**
     *
     * @param string $commaSeparated
     */
    public function setPriority($commaSeparated)
    {
        $setArray = array_flip(array_flip(explode(',', $commaSeparated)));
        $this->priority = array_combine($setArray, $setArray);
        parent::offsetSet('priority', $commaSeparated);
    }

    /**
     *
     * @return string comma separated
     */
    public function getPriority()
    {
        $commaSeparated = implode(',', $this->priority);
        parent::offsetSet('priority', $commaSeparated);
        return $commaSeparated;
    }

    public function getPriorityInt()
    {
        return $this->convertSetToInt($this->priorityStrings, $this->priority);
    }

    /**
     * データベースから取得したカラム一覧を元に、branch_setのデータが
     * ここからセットされる。
     * 一方、クライアントからデータを投入されることもあるだろう。
     * @param type $commaSeparated
     */
    public function setBranchSet($commaSeparated = null)
    {
        if (is_string($commaSeparated)) {
            $setArray = array_flip(array_flip(explode(',', $commaSeparated)));
        } elseif(is_array($commaSeparated)) {
            $setArray = $commaSeparated;
        } elseif (null === $commaSeparated) {
            $setArray = array();
        } else {
            throw new DomainException('type of branch_set is invalid');
        }
        $this->branchSet = array_combine($setArray, $setArray);
        parent::offsetSet('branch_set', implode(',', $setArray));
    }

    public function getBranchSet($byArray = false)
    {
        if ($byArray) {
            return $this->branchSet;
        }
        return implode(',', $this->branchSet);
    }

    public function convertSetToInt($setArray, $targetArray)
    {
        $keys = array_keys(array_intersect($setArray, $targetArray));
        $int = 0;
        foreach ($keys as $multi) {
            $int += pow(2, $multi);
        }
        return $int;
    }

    public function originate($domainId, $documentId, $sectionName, $sectionRev, $force = false)
    {
        $this->privateOffsetSet('domain_id', $domainId, $force);
        $this->privateOffsetSet('document_id', $documentId, $force);
        $this->privateOffsetSet('section_name', $sectionName, $force);
        $this->privateOffsetSet('section_rev', $sectionRev, $force);
        $globalSectionId = self::generateGlobalSectionId($domainId, $documentId, $sectionName, $sectionRev);
        $this->privateOffsetSet('global_section_id', $globalSectionId, $force);
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
                && $this->offsetExists($name)) {
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
                && $this->offsetExists($name)) {
            throw new DomainException('specified column is immutable. Don\'t overwrite it. make new container with new id');
        }

        if (false !== array_search($name, $this->setterGetterColumns)) {
            $setter = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));
            return $this->{$setter}($value);
        }

        return parent::offsetSet($name, $value);
    }

    public function offsetGet($name)
    {
        if (false !== array_search($name, $this->setterGetterColumns)) {
            $setter = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));
            return $this->{$setter};
        }

        return parent::offsetGet($name);
    }

}