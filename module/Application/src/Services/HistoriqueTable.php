<?php
namespace Application\Services;

use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\Sql\Select;
use Application\Model\Historique;
use Application\Model\Product;
use Application\Services\ProductTable;

class HistoriqueTable {
    protected $_tableGateway;
    private $_tableProduct;

    public function __construct(TableGatewayInterface $tableGateway, ProductTable $tableProduct){
        $this->_tableGateway = $tableGateway;
        $this->_tableProduct = $tableProduct;
    }

    public function fetchAll() { 
        $resultSet = $this->_tableGateway->select(); 
        $return = array();
        foreach( $resultSet as $r )
            $return[]=$r;
        return $return; 
    }

    public function insert(Historique $h){
        $this->_tableGateway->insert($h->toValues());
    }

    public function find($idUser){
        $resultSet = $this->_tableGateway->select(['idUser' => $idUser]);

        $return = array();
        foreach( $resultSet as $r )
            $return[]=$this->_tableProduct->find($r->_idProduct);
        return $return;
    }

    public function delete($idProduct){
        return $this->_tableGateway->delete(['idProduct' => $idProduct]);
    }

    public function insertAllToHistorique($idUser, $data){

        foreach( $data as $p ){

            $h = new Historique();
            $h->_idUser = $idUser;
            $h->_idProduct = $p->_id;

            $this->_tableGateway->insert($h->toValues());
        }
        
    }
}
?>