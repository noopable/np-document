<?php
/*
 *
 *
 * @copyright Copyright (c) 2013-2014 KipsProduction (http://www.kips.gr.jp)
 * @license   http://www.kips.gr.jp/newbsd/LICENSE.txt New BSD License
 */
namespace NpDocument\Model\Repository;

use Flower\Model\AbstractDbTableRepository;
use NpDocument\Exception\DomainException;
use NpDocument\Model\Document\AbstractDocument;
use NpDocument\Model\Document\DocumentInterface;
use NpDocument\Model\Domain\DomainAwareInterface;
use NpDocument\Model\Domain\DomainAwareTrait;
use NpDocument\Model\Repository\Section as SectionRepository;
use Zend\Db\TableGateway\TableGatewayInterface;

/**
 * @method mixed getSelect($clone = true)
 * @method mixed getEntity($where = null)
 * @method mixed getCollection($where = null, $limit = null)
 * @method mixed create
 * @method mixed save(AbstractEntity $entity, $forceInsert = false)
 * @method mixed delete(AbstractEntity $entity)
 * @method mixed setMappingMethods(array $methods)
 * @method mixed setServiceLocator
 * @method mixed getServiceLocator
 * @method mixed setRepositoryPluginManager
 * @method mixed getRepositoryPluginManager
 */
class Document extends AbstractDbTableRepository implements DomainAwareInterface
{
use DomainAwareTrait;
    /**
     *
     * @var TableGatewayInterface
     */
    protected $sectionTable;
    
    protected $sectionRepository;
    
    /**
     *　現在対象にしている組織識別ID
     * 
     * @var integer 
     */
    protected $domainId;


    public function getGlobalDocumentId($documentId)
    {
        $domainId = $this->getDomain()->getDomainId();
        return AbstractDocument::generateGlobalDocumentId($domainId, $documentId);
    }
    
    public function setDomainId($domainId = null)
    {
        if (null === $domainId) {
            $domainId = $this->getDomain()->getDomainId();
        } else {
            if ($domainId !== $this->getDomain()->getDomainId()) {
                throw new DomainException('cant\'t set domainId mismatched to injected domain');
            }
        }
        
        $this->domainId = $domainId;
    }
    
    public function getDomainId()
    {
        if (!isset($this->domainId)) {
            $this->setDomainId();
        }
        return $this->domainId;
    }
    
    public function setSectionRepository(SectionRepository $sectionRepository)
    {
        $this->sectionRepository = $sectionRepository;
    }
    
    public function getSectionRepository()
    {
        return $this->sectionRepository;
    }
    
    public function createDocument($params = null)
    {
        
    }
    
    public function getDocumentDigestCollection($where, $limit)
    {
        //Baseセクション、DigestセクションだけをJOINしたDocumentCollection
    }
    
    public function getDocumentCollection ($where, $limit = null)
    {
        $where['domain_id'] = $this->getDomainId();
    }
    
    public function getDocument($documentId)
    {
        //global化しなくても検索できるが。primary keyによる検索の方が速い。
        //branchはcurrent　branchに限定する。
        $globalDocumentId = $this->getGlobalDocumentId($documentId);
        $entity = $this->getEntity(array('global_document_id' => $globalDocumentId));
        $this->getSectionRepository()->retrieveBranchSections($entity);
        return $entity;
    }
    
    public function getDocumentBranch($documentId, $branchId)
    {
        
    }
    
    public function getDocumentOwnedCurrentUser($documentId)
    {
        
    }
    
    /**
     * すべてのブランチ、すべてのセクションを含む
     * @param type $globalDocumentId
     */
    public function getHoleDocument($documentId)
    {
        $globalDocumentId = $this->getGlobalDocumentId($documentId);
        $entity = $this->getEntity(array('global_document_id' => $globalDocumentId));
        $this->getSectionRepository()->retrieveSections($entity);
        return $entity;
    }
    
    public function findDocument($where = null)
    {
        //findでは、検索対象が問題になる。
        //sectionを検索したい場合はsectionから先に検索するべき。
        //ただし、ドキュメント検索はユーザー入力によるものではなく、
        //アプリケーション要件で検索する。
        //ユーザー入力による検索はLuceneで検索インデックスを構築するか
        //Google Search等のカスタマイズを利用する。
        $select = $this->getSelect();
        if (null !== $where) {
            $select->where($where);
        }
        $select->limit(1);
        $resultSet = $this->dao->selectWith($select);
        return $resultSet->current();
    }
    
    public function saveDocument(DocumentInterface $document)
    {
        $target = clone $document;
        $sections = $target->getSections();
        $target->setSections(array());
        //transaction start
        $this->save($target);
        $this->getSectionRepository()->saveSections($sections, false);
        //transaction end
    }
}