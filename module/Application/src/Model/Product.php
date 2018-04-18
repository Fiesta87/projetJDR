<?php
namespace Application\Model;

class Product {
    public $_id;
    public $_nom;
    public $_description;
    public $_prix;
    public $_image;

    public function __construct(){

    }

    public function exchangeArray($data) {
        $this->_id = (!empty($data['id'])) ? $data['id'] : null;
        $this->_nom = (!empty($data['nom'])) ? $data['nom'] : null;
        $this->_description = (!empty($data['description'])) ? $data['description'] : null;
        $this->_prix = (!empty($data['prix'])) ? $data['prix'] : null;
        $this->_image = (!empty($data['image'])) ? $data['image'] : null;
    }

    public function toValues(){
        return [
            'id' => $this->_id,
            'nom' => $this->_nom,
            'description' => $this->_description,
            'prix' => $this->_prix,
            'image' => $this->_image
        ];
    }
}
?>