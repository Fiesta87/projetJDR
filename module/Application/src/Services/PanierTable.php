<?php
namespace Application\Services;

use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\Sql\Select;
use Application\Model\Panier;
use Application\Model\Product;
use Application\Services\ProductTable;

class PanierTable {
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

    public function insert(Panier $p){
        $this->_tableGateway->insert($p->toValues());
    }

    public function find($idUser){
        $resultSet = $this->_tableGateway->select(['idUser' => $idUser]);

        $return = array();
        foreach( $resultSet as $r )
            $return[]=$this->_tableProduct->find($r->_idProduct);
        return $return;
    }

    public function delete($idUser, $idProduct){
        return $this->_tableGateway->delete(['idUser' => $idUser, 'idProduct' => $idProduct]);
    }

    public function deleteProduct($idProduct){
        return $this->_tableGateway->delete(['idProduct' => $idProduct]);
    }

    public function deleteAllPanierOfUser($idUser){
        return $this->_tableGateway->delete(['idUser' => $idUser]);
    }
}
?>