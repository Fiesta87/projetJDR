<?php
namespace Application\Services;

use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\Sql\Select;
use Application\Model\Userprivilege;
use Application\Model\Privilege;
use Application\Services\PrivilegeTable;

class UserprivilegeTable {
    protected $_tableGateway;
    private $_tablePrivilege;

    public function __construct(TableGatewayInterface $tableGateway, PrivilegeTable $tablePrivilege){
        $this->_tableGateway = $tableGateway;
        $this->_tablePrivilege = $tablePrivilege;
    }

    public function isAdmin($idUser){
        $idPrivilege = $this->_tableGateway->select(['idUser' => $idUser])->current()->_idPrivilege;
        return $this->_tablePrivilege->getValeurOfPrivilege($idPrivilege) == 1;
    }
}
?>