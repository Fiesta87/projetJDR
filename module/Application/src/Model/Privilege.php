<?php
namespace Application\Model;

class Privilege {
    public $_id;
    public $_valeur;

    public function __construct(){

    }

    public function exchangeArray($data) {
        $this->_id = (!empty($data['id'])) ? $data['id'] : null;
        $this->_valeur = (!empty($data['valeur'])) ? $data['valeur'] : null;
    }

    public function toValues(){
        return [
            'id' => $this->_id,
            'valeur' => $this->_valeur,
        ];
    }
}
?>