<?php
/*
 *
 *
 * @copyright Copyright (c) 2013-2014 KipsProduction (http://www.kips.gr.jp)
 * @license   http://www.kips.gr.jp/newbsd/LICENSE.txt New BSD License
 */
namespace NpDocument\Model\Document;

use Flower\Model\AbstractEntity;
use NpDocument\Model\Exception\DomainException;
use NpDocument\Model\Exception\RuntimeException;
use NpDocument\Model\Document\DocumentInterface;
use NpDocument\Model\Document\Service\AbstractService;

/**
 *
 *
 */
abstract class AbstractDocument extends AbstractEntity implements DocumentInterface
{

    protected $defaultSectionsDef;

    protected $services;

    protected $sections;

    public function getIdentifier()
    {
        return array('domain_id', 'document_id');
    }

    public function getGlobalDocumentId()
    {
        if (!isset($this->global_document_id)) {
            if (isset($this->domain_id) && $this->document_id) {
                $this->global_document_id = self::generateGlobalDocumentId($this->domain_id, $this->document_id);
            } else {
                throw DomainException('This document has no identity');
            }
        }
        return $this->global_document_id;
    }
    /**
     * @todo use Validator with DI
     * @todo remove static method from standard class, use abstract or another
     *
     * @see data/resource/document_before_insert.trigger
     *
     * @param integer $domainId
     * @param integer $documentId
     */
    public static function generateGlobalDocumentId($domainId, $documentId)
    {
        if (!is_int($domainId) || $domainId <= 0) {
            throw new DomainException('domain_id is invalid it should be unsigned integer');
        }

        if (!is_int($documentId) || $documentId <= 0) {
            throw new DomainException('document_id is invalid it should be unsigned integer');
        }

        if ($domainId > hexdec('FFFFFF')) {
            throw new DomainException('domain_id is too large');
        }

        if ($documentId > hexdec('FFFFFF')) {
            throw new DomainException('document_id is too large');
        }
        return sprintf('%06X' . DocumentInterface::GLOBAL_DOCUMENT_DELIMITER . '%06X', $domainId, $documentId);

    }

    /**
     *
     */
    public function getDefaultSectionsDef()
    {
        return $this->defaultSectionsDef;
    }

    public function getSections()
    {
        return $this->sections;
    }

    public function setSections(array $sections)
    {
        $this->sections = $sections;
    }

    /**
     *
     * @param string $name
     * @param \NpDocument\Model\Document\Service\AbstractService $service
     */
    public function setService($name, AbstractService $service)
    {
        $this->services[$name] = $service;
    }

    public function removeService($name)
    {
        if (isset($this->services[$name])) {
            unset($this->services[$name]);
        }
    }

    public function __call($name, $arguments)
    {
        if (($pos = strpos($name, '_')) > 0 ) {
            list($serviceName, $methodName) = array_merge(explode('_', $name, 2), array(''));
            if (isset($this->services[$serviceName])) {
                $service = $this->services[$serviceName];
                if (method_exists($service, $methodName)) {
                    return call_user_func_array(array($service, $methodName), $arguments);
                }
            }
        }
        throw new RuntimeException('method can\'t call ' . $name);
    }
}