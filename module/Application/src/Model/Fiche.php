<?php
namespace Application\Model;

class Fiche {
    public $_id;
    public $_nom;
    public $_description;

    public function __construct(){

    }

    public function exchangeArray($data) {
        $this->_id = (!empty($data['id'])) ? $data['id'] : null;
        $this->_nom = (!empty($data['nom'])) ? $data['nom'] : null;
        $this->_description = (!empty($data['description'])) ? $data['description'] : null;
    }

    public function toValues(){
        return [
            'id' => $this->_id,
            'nom' => $this->_nom,
            'description' => $this->_description,
        ];
    }
}
?>