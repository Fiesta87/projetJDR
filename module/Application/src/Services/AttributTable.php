<?php
namespace Application\Services;

use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\Sql\Select;
use Application\Model\Attribut;

class AttributTable {
    protected $_tableGateway;

    public function __construct(TableGatewayInterface $tableGateway){
        $this->_tableGateway = $tableGateway;
    }

    public function getAttributsOfFiche($idFiche) { 
        $resultSet = $this->_tableGateway->select(['idFiche' => $idFiche, 'idAttributParent' => -1]);
        $return = array();
        foreach( $resultSet as $r ){
            $r->_sousAttributs = $this->findChildrenOf($r->_id);
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
            $r->_sousAttributs = $this->findChildrenOf($r->_id);
            $return[]=$r;
        }
        return $return;
    }

    public function update(Attribut $toUpdate, $data){
        return $this->_tableGateway->update($data,['id' => $toUpdate->_id]);
    }

    public function deleteAllAttributsOfFiche($idFiche){

        $resultSet = $this->_tableGateway->select(['idFiche' => $idFiche]);

        foreach( $resultSet as $r ){
            $this->_tableGateway->delete(['id' => $r->_id]);
        }
    }

    public function deleteAttributAndHisSousAttributs($idAttributToDelete){

        $resultSet = $this->_tableGateway->select(['idAttributParent' => $idAttributToDelete]);

        foreach( $resultSet as $r ){
            $this->deleteAttributAndHisSousAttributs($r->_id);
        }

        $this->_tableGateway->delete(['id' => $idAttributToDelete]);
    }
}
?>