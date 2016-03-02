<?php
/*
 *
 *
 * @copyright Copyright (c) 2013-2014 KipsProduction (http://www.kips.gr.jp)
 * @license   http://www.kips.gr.jp/newbsd/LICENSE.txt New BSD License
 */
namespace NpDocument\Model\Repository;

use Flower\Domain\DomainAwareInterface;
use Flower\Domain\DomainAwareTrait;
use Flower\Model\SelectStrategyInterface;
use Flower\Model\AbstractDbTableRepository;
use NpDocument\Exception\DomainException;
use NpDocument\Model\Document\AbstractDocument;
use NpDocument\Model\Document\DocumentInterface;
use NpDocument\Model\Exception\RuntimeException as ModelRuntimeException;
use NpDocument\Model\Repository\DocumentLink as DocumentLinkRepository;
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

    protected $sectionRepository;

    protected $documentLinkRepository;

    /**
     *　現在対象にしている組織識別ID
     *
     * @var integer
     */
    protected $domainId;


    public function getGlobalDocumentId($documentId)
    {
        return AbstractDocument::generateGlobalDocumentId((int) $this->getDomainId(), (int) $documentId);
    }

    public function setDomainId($domainId = null)
    {
        if (null === $domainId) {
            $domain = $this->getDomain();
            if (null === $domain) {
                $domainId = 0;
            } else {
                $domainId = $domain->getDomainId();
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

    public function initialize()
    {
        parent::initialize();
        if (isset($this->sectionRepository)) {
            $this->sectionRepository->initialize();
        }
        if (isset($this->documentLinkRepository)) {
            $this->documentLinkRepository->initialize();
        }
    }

    public function setSectionRepository(SectionRepository $sectionRepository)
    {
        $this->sectionRepository = $sectionRepository;
    }

    public function getSectionRepository()
    {
        return $this->sectionRepository;
    }

    public function setDocumentLinkRepository(DocumentLinkRepository $documentLinkRepository)
    {
        $this->documentLinkRepository = $documentLinkRepository;
    }

    public function getDocumentLinkRepository()
    {
        return $this->documentLinkRepository;
    }

    public function createDocument($params = null)
    {
        $protoType = $this->getEntityPrototype();
        $document = clone $protoType;

        if (isset($params['domain_id'])) {
            $domainId = $document->domain_id = $params['domain_id'];
            unset($params['domain_id']);
        } elseif (isset($document->domain_id)) {
            //for util
            $domainId = $document->domain_id;
        } else {
            $domainId = $document->domain_id = $this->getDomainId();
        }

        if (isset($params['document_id'])) {
            $documentId = $document->document_id = $params['document_id'];
            unset($params['document_id']);
        } elseif (isset($document->document_id)) {
            $documentId = $document->document_id;
        }
        //document_idはnullで構わない。
        if (isset($documentId) && !isset($document->global_document_id)) {
            $document->global_document_id = AbstractDocument::generateGlobalDocumentId($domainId, $documentId);
        }

        if ($document instanceof AbstractDocument) {
            $document->create_init($this);
        }

        if ($params) {
            $document->exchangeArray($params);
        }

        return $document;
    }

    /**
     * 素のリストとして取得（あまり使わないと思う)
     * @param array $where
     * @param type $limit
     */
    public function getDocumentCollection ($where, $limit = null)
    {
        $where['domain_id'] = $this->getDomainId();
        $resultSet = $this->getCollection($where);
        return $this->getCollectionFromResultSet($resultSet);
    }

    public function getDocumentWithStrategy(SelectStrategyInterface $strategy)
    {
        $resultSet = $this->getCollectionWithStrategy($strategy);
        return $this->getCollectionFromResultSet($resultSet);
    }


    public function getDigestCollection($where, $limit)
    {
        $digestStrategy = new DigestStrategy;
        $digestStrategy->setWhere($where);
        $digestStrategy->setLimit($limit);
        $resultSet = $this->getCollectionWithStrategy($digestStrategy);
        return $this->getCollectionFromResultSet($resultSet);
    }

    public function getDocumentByName($documentName)
    {
        //グローバルに一意ではない。せめて、domainで絞る。
        $where = array(
            'domain_id' => $this->getDomainId(),
            'document_name' => (string) $documentName,
        );
        $entity = $this->getEntity($where);
        $this->getSectionRepository()->retrieveBranchSections($entity);
        $this->getDocumentLinkRepository()->retrieveDocumentLinks($entity);
        return $entity;
    }

    public function getDocument($documentId)
    {
        //global化しなくても検索できるが。primary keyによる検索の方が速い。
        //branchはcurrent　branchに限定する。
        $globalDocumentId = $this->getGlobalDocumentId($documentId);
        $entity = $this->getEntity(array('global_document_id' => $globalDocumentId));
        return $this->retrieveRelations($entity);
    }

    public function getCollectionFromResultSet($resultSet)
    {
        $collection = array();
        foreach ($resultSet as $entity) {
            $collection[] = $this->retrieveRelations($entity);
        }
        return $collection;
    }

    public function retrieveRelations($entity)
    {
        $this->getSectionRepository()->retrieveBranchSections($entity);
        $this->getDocumentLinkRepository()->retrieveDocumentLinks($entity);
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
    public function getWholeDocument($documentId)
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
        $target->object_hash = md5(uniqid(spl_object_hash($document)));
        //配列だけど、クローンするとプロパティもクローンする？
        $sections = $target->getSections();
        $links = $target->getLinks();
        $target->setSections(array());

        $this->beginTransaction();
        try {
            $res = $this->save($target);

            if (! $res) {
                throw new ModelRuntimeException('save failed please check document db log');
            }
            /**
             * この段階でinsertが行われてlast_insert_idがとれるのか、
             * identifierが有効でupdateが行われたのかを判断するのは難しい。
             *
             * データベース側にパンくずを巻いておいてトリガーで自動作成されたデータを回収する。
             */
            $saved = $this->getEntity(['object_hash' => $target->object_hash]);
            //見つからない場合・・・
            if (! $saved) {
                throw new ModelRuntimeException('item lost at save process');
            }
            foreach ($saved as $key => $val) {
                $document->{$key} = $val;
            }

            if ($sections) {
                $this->getSectionRepository()->saveSections($sections, $document);
            }

            if ($links) {
                $this->getDocumentLinkRepository()->saveLinks($links);
            }
            $this->commit();
        } catch (\Exception $ex) {
            $this->rollback();
            throw $ex;
        }
        return true;
    }
}