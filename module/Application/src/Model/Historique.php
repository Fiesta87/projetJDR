<?php
namespace Application\Model;

class Historique {
    public $_idUser;
    public $_idProduct;

    public function __construct(){

    }

    public function exchangeArray($data) {
        $this->_idUser = (!empty($data['idUser'])) ? $data['idUser'] : null;
        $this->_idProduct = (!empty($data['idProduct'])) ? $data['idProduct'] : null;
    }

    public function toValues(){
        return [
            'idUser' => $this->_idUser,
            'idProduct' => $this->_idProduct,
        ];
    }
}
?>