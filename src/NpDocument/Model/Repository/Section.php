<?php
namespace NpDocument\Model\Repository;

use Flower\Domain\DomainAwareInterface;
use Flower\Domain\DomainAwareTrait;
use Flower\Model\AbstractDbTableRepository;
use Flower\Model\AbstractEntity;
use NpDocument\Exception\RuntimeException;
use NpDocument\Exception\DomainException;
use NpDocument\Model\Document\DocumentInterface;
use NpDocument\Model\Section\Config;
use NpDocument\Model\Section\SectionInterface;
use NpDocument\Model\Section\SectionPluginManager;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\StdLib\ArrayUtils;

class Section extends AbstractDbTableRepository implements DomainAwareInterface
{
    use DomainAwareTrait;

    /**
     *
     * @var SectionPluginManager
     */
    protected $sectionPluginManager;
    /**
     * columns 対応
     *  使用する場合は、対象とするカラム名をすべて列挙すること。
     * 指定しなければ自動的にデータベースのカラム名が利用される。
     *
     * key = 物理名
     * value = DB column
     * @var array
     */
    //protected $columns = array(
    //    'document_id' => 'document_id',
    //    'fullname' => 'fullname',
    //);

    /**
     *
     * @param $name
     * @param $entityPrototype
     * @param $tableGateway
     */
    public function __construct($name = null, $entityPrototype, TableGatewayInterface $tableGateway)
    {
        //$this->setOption('columns', $this->columns);
        parent::__construct($name, $entityPrototype, $tableGateway);
    }

    public function setSectionPluginManager(SectionPluginManager $sectionPluginManager)
    {
        $this->sectionPluginManager = $sectionPluginManager;
    }

    public function getSectionPluginManager()
    {
        if (!isset($this->sectionPluginManager)) {
            throw new RuntimeException('Section Repository needs SectionPluginManager');
        }
        return $this->sectionPluginManager;
    }

    /**
     * createSectionではデータ投入までは行わない
     *
     * @param string $type
     * @param array $params
     * @return type
     */
    public function createSection($type = null, array $params = array())
    {
        if (null === $type) {
            $type = 'Section';
        }

        if (!isset($params['data_container'])) {
            $params['data_container'] = $this->create();
        }
        $config = new Config($params);

        /**
         * AbstractPluginManager::createFromInvokable
         * にてconfigureされる
         */
        $section = $this->getSectionPluginManager()->get($type, $config);
        //初期値を設定
        $section->section_rev = 1;
        return $section;
    }

    public function associateDocument(DocumentInterface $document, SectionInterface $section)
    {
        $dataContainer = $section->getDataContainer();
        $dataContainer->originate(
                $document->domain_id,
                $document->document_id,
                $dataContainer->section_name,
                $dataContainer->section_rev,
                true);
        return $this;
    }


    public function saveSections(array $sections, DocumentInterface $document = null)
    {
        foreach ($sections as $section) {
            if ($section instanceof SectionInterface) {
                $this->saveSection($section, $document);
            }
        }
    }

    public function saveSection(SectionInterface $section, DocumentInterface $document = null)
    {
        if (isset($document)) {
            $this->associateDocument($document, $section);
        }
        $dataContainer = $section->getDataContainer();
        return $this->save($dataContainer);
    }

    public function findSection($globalSectionId)
    {
        $dataContainer = $this->getEntity(array('global_section_id' => $globalSectionId));
        if ($dataContainer instanceof AbstractEntity) {
            return $this->retrieveSectionFromDataContainer($dataContainer);
        } else {
            return null;
        }
    }

    public function retrieveSectionFromDataContainer(AbstractEntity $dataContainer)
    {
        if (!isset($dataContainer->section_class)) {
            throw new DomainException('column section_class should be valid class shortname');
        }
        $type = $dataContainer->section_class;
        $params = array('data_container' => $dataContainer);
        $config = new Config($params);
        try {
            $section = $this->getSectionPluginManager()->get($type, $config);
        } catch (ServiceNotFoundException $ex) {
            throw new DomainException('try to create section from a data container. but cannot find section class named ' . $type, 0, $ex);
        }

        return $section;
    }

    /**
     * 実装を簡易にするため、$whereはarrayに限定する
     * @param \NpDocument\Model\Document\DocumentInterface $document
     * @param array $where
     */
    public function retrieveSections(DocumentInterface $document, array $where = array())
    {
        $where['global_document_id'] = $document->getGlobalDocumentId();

        $collection = $this->getSectionRepository()->getCollection($where);
        $sections = ArrayUtils::iteratorToArray($collection, false);
        $document->setSections($sections);
    }

    public function retrieveBranchSections(DocumentInterface $document)
    {
        $branch = $document->branch;
        $globalDocumentId = $document->getGlobalDocumentId();
        //@todo branch_setに対するwhereを作成して取得
    }
}