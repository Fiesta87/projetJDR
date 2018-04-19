<?php
namespace Application\Services;

use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\Sql\Select;
use Application\Model\Fiche;
use Application\Services\MetadataTable;
use Application\Services\AttributTable;
use User\Services\UserManager;

class FicheTable {
    protected $_tableGateway;
    private $_tableMetadata;
    private $_tableAttribut;
    private $_tableUser;

    private $offset;
    private $nbFicheParPage;

    public function __construct(TableGatewayInterface $tableGateway, MetadataTable $tableMetadata, AttributTable $tableAttribut, UserManager $tableUser){
        $this->_tableGateway = $tableGateway;
        $this->_tableMetadata = $tableMetadata;
        $this->_tableAttribut = $tableAttribut;
        $this->_tableUser = $tableUser;
    }


    /**
     * Retourne la liste des fiches d'une page de la galerie.
     * Les fiches retournées n'ont pas leur listes des attributs initialisé.
     * L'utilisateur de la fiche est initialisé.
     * Utilisez la méthode find pour récupérer une fiche compléte.
     */
    public function fetchPage($page) {

        // on récupère le nombre d'article par page spécifié dans la BD (Metadata)
        $this->nbFicheParPage = intval($this->_tableMetadata->findByNom('page')->_valeur);

        $this->offset = ($page-1) * $this->nbFicheParPage;

        // pagination
        $resultSet = $this->_tableGateway->select(function (Select $select) {
            $select->limit($this->nbFicheParPage)->offset($this->offset);
        });

        $return = array();
        foreach( $resultSet as $r ){
            $r->_userName = $this->_tableUser->getNameOfUser($r->_idUser);
            $return[]=$r;
        }
            
        return $return;
    }

    public function insert(Fiche $f){
        $this->_tableGateway->insert($f->toValues());
    }

    /**
     * Retourne une fiche avec ses attributs
     */
    public function find($id){
        $f = $this->_tableGateway->select(['id' => $id])->current();

        $f->_attributs = $this->_tableAttribut->getAttributsOfFiche($f->_id);

        $f->_userName = $this->_tableUser->getNameOfUser($f->_idUser);

        return $f;
    }

    /**
     * Retourne une fiche sans ses attributs
     */
    public function findNoAttribut($id){
        $f = $this->_tableGateway->select(['id' => $id])->current();

        $f->_userName = $this->_tableUser->getNameOfUser($f->_idUser);

        return $f;
    }

    /**
     * Retourne toutes les fiches sans leur attributs d'un utilisateur
     */
    public function getFichesOfUser($idUser){

        $resultSet = $this->_tableGateway->select(['idUser' => $idUser]); 
        $return = array();
        foreach( $resultSet as $r )
            $return[]=$r;
        return $return;
    }

    public function delete(Fiche $toDelete){
        return $this->_tableGateway->delete(['id' => $toDelete->_id]);
    }

    public function update(Fiche $toUpdate, $data){
        return $this->_tableGateway->update($data,['id' => $toUpdate->_id]);
    }
}
?>