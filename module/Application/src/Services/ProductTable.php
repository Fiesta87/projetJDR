<?php
namespace Application\Services;

use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\Sql\Select;
use Application\Model\Product;
use Application\Services\MetadataTable;

class ProductTable {
    protected $_tableGateway;
    private $_tableMetadata;

    private $offset;
    private $nbProductPerPage;

    public function __construct(TableGatewayInterface $tableGateway, MetadataTable $tableMetadata){
        $this->_tableGateway = $tableGateway;
        $this->_tableMetadata = $tableMetadata;
    }

    public function fetchAll() { 
        $resultSet = $this->_tableGateway->select(); 
        $return = array();
        foreach( $resultSet as $r )
            $return[]=$r;
        return $return; 
    }

    public function fetchPage($page) {

        // on récupère le nombre d'article par page spécifié dans la BD (Metadata)
        $this->nbProductPerPage = intval($this->_tableMetadata->findByNom('page')->_valeur);

        $this->offset = ($page-1) * $this->nbProductPerPage;

        // pagination
        $resultSet = $this->_tableGateway->select(function (Select $select) {
            $select->limit($this->nbProductPerPage)->offset($this->offset);
        });

        $return = array();
        foreach( $resultSet as $r )
            $return[]=$r;
        return $return; 
    }

    public function insert(Product $a){
        $this->_tableGateway->insert($a->toValues());
    }

    public function find($id){
        return $this->_tableGateway->select(['id' => $id])->current();
    }

    public function delete(Product $toDelete){
        return $this->_tableGateway->delete(['id' => $toDelete->_id]);
    }

    public function update(Product $toUpdate, $data){
        return $this->_tableGateway->update($data,['id' => $toUpdate->_id]);
    }
}
?>