<?php
namespace Application\Model;

class Fiche {

    /** Partie BD **/

    public $_id;
    public $_nom;
    public $_description;
    public $_idUser;

    /** Partie Objet **/

    public $_attributs;
    public $_userName;

    public function __construct(){

    }

    public function exchangeArray($data) {
        $this->_id = (!empty($data['id'])) ? $data['id'] : null;
        $this->_nom = (!empty($data['nom'])) ? $data['nom'] : null;
        $this->_description = (!empty($data['description'])) ? $data['description'] : null;
        $this->_idUser = (!empty($data['idUser'])) ? $data['idUser'] : null;
    }

    public function toValues(){
        return [
            'id' => $this->_id,
            'nom' => $this->_nom,
            'description' => $this->_description,
            'idUser' => $this->_idUser,
        ];
    }

    public function toXML(){
        $noeud = array();
        $noeud[0] = new NoeudXml();
        $noeud[0]->name ="fichePersoType";

        $noeudChild = array();
        $noeudChild[0] = new NoeudXml();
        $noeudChild[0]->name = "Attributs";

        $noeudChildChild = array();

        $i = 0;

        foreach($this->_attributs as $a){

            $noeudChildChild[$i] = new NoeudXml();

            $noeudChildChild[$i]->name = str_replace(" ", "_", $a->_nom);
            $noeudChildChild[$i]->attribute['name'] = "valeur";
            $noeudChildChild[$i]->attribute['value'] = $a->_valeur;

            $noeudChildChild[$i]->child = array();

            $j = 0;

            foreach($a->_sousAttributs as $a2){
                $noeudChildChild[$i]->child[$j] = new NoeudXml();
                $noeudChildChild[$i]->child[$j]->name = str_replace(" ", "_", $a2->_nom);
                $noeudChildChild[$i]->child[$j]->attribute['name'] = "valeur";
                $noeudChildChild[$i]->child[$j]->attribute['value'] = $a2->_valeur;

                $j++;
            }

            $i++;
        }

        $noeudChild[0]->child=$noeudChildChild;
        $noeud[0]->child=$noeudChild;

        return $noeud;
    }
}
?>