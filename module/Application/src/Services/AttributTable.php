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

    public function getAttributsOfFiche($idFiche) { 
        $resultSet = $this->_tableGateway->select(['idFiche' => $idFiche, 'idAttributParent' => -1]);
        $return = array();
        foreach( $resultSet as $r ){
            $r->_sousAttributs = findChildrenOf($r->_id);
            $return[]=$r;
        }
        return $return; 
    }

    public function insert(Attribut $a){
        $this->_tableGateway->insert($a->toValues());
    }

    public function find($id){
        return $this->_tableGateway->select(['id' => $id])->current();
    }

    public function findChildrenOf($idAttributParent){
        $resultSet = $this->_tableGateway->select(['idAttributParent' => $idAttributParent]);
        $return = array();
        foreach( $resultSet as $r ){
            $r->_sousAttributs = findChildrenOf($r->_id);
            $return[]=$r;
        }
        return $return;
    }

    public function delete(Attribut $toDelete){
        return $this->_tableGateway->delete(['id' => $toDelete->_id]);
    }

    public function update(Attribut $toUpdate, $data){
        return $this->_tableGateway->update($data,['id' => $toUpdate->_id]);
    }
}
?>