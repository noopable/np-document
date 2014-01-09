<?php
namespace NpDocument\Model\Repository;

use Flower\Model\AbstractDbTableRepository;
use NpDocument\Exception\RuntimeException;
use NpDocument\Model\Repository\Section as SectionRepository;
use Zend\Db\TableGateway\TableGatewayInterface;

class Document extends AbstractDbTableRepository
{

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


    public function setSectionTable(TableGatewayInterface $sectionTable)
    {
        $this->sectionTable = $sectionTable;
    }
    
    public function getSectionTable()
    {
        if (!isset($this->sectionTable)) {
            if (isset($this->sectionRepository)) {
                $this->sectionTable = $this->sectionRepository->getTableGateway();
            } else {
                throw new RuntimeException('Document repository needs section table or section repository');
            }
        }
        return $this->sectionTable;
    }
    
    public function setSectionRepository(SectionRepository $sectionRepository)
    {
        $this->sectionRepository = $sectionRepository;
    }
    
    public function getSectionRepository()
    {
        return $this->sectionRepository;
    }
    
    public function getDocument($where = null)
    {
        $select = $this->getSelect();
        if (null !== $where) {
            $select->where($where);
        }
        $select->limit(1);
        $resultSet = $this->dao->selectWith($select);
        return $resultSet->current();
    }
    
    public function setDomainId($domainId)
    {
        $this->domainId = $domainId;
    }
}