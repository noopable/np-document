<?php
namespace NpDocument\Model\Repository;

use Flower\Model\AbstractDbTableRepository;
use Zend\Db\TableGateway\TableGatewayInterface;

class Section extends AbstractDbTableRepository
{

    /**
     * columns 対応
     *  使用する場合は、対象とするカラム名をすべて列挙すること。
     * 指定しなければ自動的にデータベースのカラム名が利用される。
     * 
     * key = 物理名
     * value = DB column
     * @var array
     */
    protected $columns = array(
        'document_id' => 'document_id',
        'fullname' => 'fullname',
    );

    /**
     *
     * @param $name
     * @param $entityPrototype
     * @param $tableGateway
     */
    public function __construct($name = null, $entityPrototype, TableGatewayInterface $tableGateway)
    {
        $this->setOption('columns', $this->columns);
        parent::__construct($name, $entityPrototype, $tableGateway);
    }

}