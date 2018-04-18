<?php
namespace Application\Services;

use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\Sql\Select;
use Application\Model\Privilege;

class PrivilegeTable {
    protected $_tableGateway;

    public function __construct(TableGatewayInterface $tableGateway){
        $this->_tableGateway = $tableGateway;
    }

    public function getValeurOfPrivilege($id){
        return $this->_tableGateway->select(['id' => $id])->current()->_valeur;
    }
}
?>