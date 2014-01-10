<?php
namespace NpDocument\Model\Repository;

use Flower\Model\AbstractDbTableRepository;
use Zend\Db\TableGateway\TableGatewayInterface;
use NpDocument\Exception\RuntimeException;
use NpDocument\Model\Section\SectionPluginManager;

class Section extends AbstractDbTableRepository
{

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

        return $this->getSectionPluginManager()->get($type, $config);
    }

}