<?php
namespace Application\Model;

class Metadata {
    public $_id;
    public $_nom;
    public $_valeur;

    public function __construct(){

    }

    public function exchangeArray($data) {
        $this->_id = (!empty($data['id'])) ? $data['id'] : null;
        $this->_nom = (!empty($data['nom'])) ? $data['nom'] : null;
        $this->_valeur = (!empty($data['valeur'])) ? $data['valeur'] : null;
    }

    public function toValues(){
        return [
            'id' => $this->_id,
            'nom' => $this->_nom,
            'valeur' => $this->_valeur,
        ];
    }
}
?>