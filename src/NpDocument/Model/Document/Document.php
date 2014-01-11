<?php
namespace NpDocument\Model\Document;

use Flower\Model\AbstractEntity;
use NpDocument\Exception\DomainException;

class Document extends AbstractEntity implements DocumentInterface
{
    protected $authenticated = false;

    protected $sections;
    
    public function getIdentifier()
    {
        return array('domain_id', 'document_id');
    }
    
    /**
     * @todo use Validator with DI
     * @todo remove static method from standard class, use abstract or another
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
    
    public function getSections()
    {
        //priorityQueue?
    }
}