<?php
namespace Application\Services;

use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\Sql\Select;
use Application\Model\Fiche;
use Application\Services\MetadataTable;
use Application\Services\AttributTable;

class FicheTable {
    protected $_tableGateway;
    private $_tableMetadata;
    private $_tableAttribut;

    private $offset;
    private $nbFicheParPage;

    public function __construct(TableGatewayInterface $tableGateway, MetadataTable $tableMetadata, AttributTable $tableAttribut){
        $this->_tableGateway = $tableGateway;
        $this->_tableMetadata = $tableMetadata;
        $this->_tableAttribut = $tableAttribut;
    }


    /**
     * Retourne la liste des fiches d'une page de la galerie.
     * Les fiches retournées n'ont pas leur listes des attributs initialisé.
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
        foreach( $resultSet as $r )
            $return[]=$r;
        return $return; 
    }

    public function insert(Fiche $f){
        $this->_tableGateway->insert($f->toValues());
    }

    /**
     * Retourne un fiche avec ses attributs
     */
    public function find($id){
        $f = $this->_tableGateway->select(['id' => $id])->current();

        $f->_attributs = $this->_tableAttribut->getAttributsOfFiche($f->_id);

        return $f;
    }

    public function delete(Fiche $toDelete){
        return $this->_tableGateway->delete(['id' => $toDelete->_id]);
    }

    public function update(Fiche $toUpdate, $data){
        return $this->_tableGateway->update($data,['id' => $toUpdate->_id]);
    }
}
?>