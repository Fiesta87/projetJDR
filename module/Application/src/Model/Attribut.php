<?php
namespace Application\Model;

class Attribut {
    public $_id;
    public $_idFiche;
    public $_idAttributParent;
    public $_nom;
    public $_valeur;
    public $_sousAttributs;

    public function __construct(){

    }

    public function exchangeArray($data) {
        $this->_id = (!empty($data['id'])) ? $data['id'] : null;
        $this->_idFiche = (!empty($data['idFiche'])) ? $data['idFiche'] : null;
        $this->_idAttributParent = (!empty($data['idAttributParent'])) ? $data['idAttributParent'] : null;
        $this->_nom = (!empty($data['nom'])) ? $data['nom'] : null;
        $this->_valeur = (!empty($data['valeur'])) ? $data['valeur'] : null;
    }

    public function toValues(){
        return [
            'id' => $this->_id,
            'idFiche' => $this->_idFiche,
            'idAttributParent' => $this->_idAttributParent,
            'nom' => $this->_nom,
            'valeur' => $this->_valeur,
        ];
    }
}
?>