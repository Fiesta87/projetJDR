<?php
namespace Application\Model;

class Favoris {
    public $_idUser;
    public $_idFiche;

    public function __construct(){

    }

    public function exchangeArray($data) {
        $this->_idUser = (!empty($data['idUser'])) ? $data['idUser'] : null;
        $this->_idFiche = (!empty($data['idFiche'])) ? $data['idFiche'] : null;
    }

    public function toValues(){
        return [
            'idUser' => $this->_idUser,
            'idFiche' => $this->_idFiche,
        ];
    }
}
?>