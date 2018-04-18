<?php
namespace Application\Services;

use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\Sql\Select;
use Application\Model\Attribut;
use Application\Services\MetadataTable;

class FicheTable {
    protected $_tableGateway;
    private $_tableMetadata;

    public function __construct(TableGatewayInterface $tableGateway, MetadataTable $tableMetadata){
        $this->_tableGateway = $tableGateway;
        $this->_tableMetadata = $tableMetadata;
    }

    public function getAttributOfFiche($idFiche) { 
        $resultSet = $this->_tableGateway->select(); 
        $return = array();
        foreach( $resultSet as $r )
            $return[]=$r;
        return $return; 
    }

    public function insert(Attribut $a){
        $this->_tableGateway->insert($a->toValues());
    }

    public function find($id){
        return $this->_tableGateway->select(['id' => $id])->current();
    }

    public function delete(Attribut $toDelete){
        return $this->_tableGateway->delete(['id' => $toDelete->_id]);
    }

    public function update(Attribut $toUpdate, $data){
        return $this->_tableGateway->update($data,['id' => $toUpdate->_id]);
    }
}
?>