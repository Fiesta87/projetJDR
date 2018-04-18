<?php
namespace Application\Model;

class Userprivilege {
    public $_idUser;
    public $_idPrivilege;

    public function __construct(){

    }

    public function exchangeArray($data) {
        $this->_idUser = (!empty($data['idUser'])) ? $data['idUser'] : null;
        $this->_idPrivilege = (!empty($data['idPrivilege'])) ? $data['idPrivilege'] : null;
    }

    public function toValues(){
        return [
            'idUser' => $this->_idUser,
            'idPrivilege' => $this->_idPrivilege,
        ];
    }
}
?>