<?php
namespace NpDocument\Model\Document\DocumentClass;

use Flower\Model\AbstractEntity;
use NpDocument\Exception\DomainException;
use NpDocument\Model\Document\DocumentInterface;

abstract class AbstractDocument extends AbstractEntity implements DocumentInterface
{

    protected $sections;
    
    public function getIdentifier()
    {
        return array('domain_id', 'document_id');
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
    abstract public function getSections();
}