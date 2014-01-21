<?php
namespace NpDocument\Model\Repository;


use Flower\Model\AbstractDbTableRepository;
use Flower\Model\AbstractEntity;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use NpDocument\Exception\RuntimeException;
use NpDocument\Exception\DomainException;
use NpDocument\Model\Domain\DomainAwareInterface;
use NpDocument\Model\Domain\DomainAwareTrait;
use NpDocument\Model\Document\DocumentInterface;
use NpDocument\Model\Section\Config;
use NpDocument\Model\Section\SectionInterface;
use NpDocument\Model\Section\SectionPluginManager;

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
    
    public function createSection($type = null, array $params = array())
    {
        if (null === $type) {
            $type = 'Section';
        }
        
        if (!isset($params['data_container'])) {
            $params['data_container'] = $this->create();
        }
        $config = new Config($params);

        return $this->getSectionPluginManager()->get($type, $config);
    }
    
    public function saveSections(array $sections, $transaction = true)
    {
        if ($transaction) {
            //start transaction
        }
        foreach ($sections as $section) {
            if ($section instanceof SectionInterface) {
                $this->saveSection($section);
            }
        }
        if ($transaction) {
            //end transaction
        }
    }
    
    public function saveSection(SectionInterface $section)
    {
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
        
        $sections = $this->getSectionRepository()->getCollection($where);
        $document->setSections($sections);
    }
    
    public function retrieveBranchSections(DocumentInterface $document)
    {
        $branch = $document->branch;
        $globalDocumentId = $document->getGlobalDocumentId();
        //@todo branch_setに対するwhereを作成して取得
    }
}