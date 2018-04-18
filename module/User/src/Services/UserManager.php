<?php
namespace User\Services;

use Zend\Db\TableGateway\TableGatewayInterface;

class UserManager {
    protected $_tableGateway;

    public function __construct(TableGatewayInterface $tableGateway){
        $this->_tableGateway = $tableGateway;
    }

    public function findByUserEmail($useremail){
        return $this->_tableGateway->select(['email' => $useremail])->current();
    }

    public function getNameOfUser($id){
        return $this->_tableGateway->select(['id' => $id])->current()->_username;
    }

    public function update($id, $data){
        return $this->_tableGateway->update($data,['id' => $id]);
    }
}
?>