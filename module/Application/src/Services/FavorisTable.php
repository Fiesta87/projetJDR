<?php
namespace Application\Services;

use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\Sql\Select;
use Application\Model\Favoris;
use Application\Services\FicheTable;

class FavorisTable {
    protected $_tableGateway;
    private $_tableFiche;

    public function __construct(TableGatewayInterface $tableGateway, FicheTable $tableFiche){
        $this->_tableGateway = $tableGateway;
        $this->_tableFiche = $tableFiche;
    }

    /**
     * Retourne toutes les fiches favoris d'un utilisateur
     */
    public function getFichesFavorisOfUser($idUser){

        $resultSet = $this->_tableGateway->select(['idUser' => $idUser]); 
        $return = array();
        foreach( $resultSet as $r )
            $return[]=$this->_tableFiche->findNoAttribut($r->_idFiche);
        return $return;
    }

    public function insert(Favoris $f){
        $this->_tableGateway->insert($f->toValues());
    }

    public function isInFavorisOfUser($idFiche, $idUser){
        return $this->_tableGateway->select(['idFiche' => $idFiche, 'idUser' => $idUser])->current() != null;
    }

    public function delete(Favoris $toDelete){
        return $this->_tableGateway->delete(['idUser' => $toDelete->_idUser, 'idFiche' => $toDelete->_idFiche]);
    }

    public function deleteFicheFromFavoris($idFiche){
        return $this->_tableGateway->delete(['idFiche' => $idFiche]);
    }

    public function get5MostFavorite(){
        $resultSet = $this->_tableGateway->select(function (Select $select) {
            $select->columns(array('idFiche', 'num' => new \Zend\Db\Sql\Expression('COUNT(idFiche)')))->group('idFiche')->order('num DESC')->limit(5);
        });

        $return = array();
        foreach( $resultSet as $r ){
            $r = $this->_tableFiche->find($r->_idFiche);
            $return[]=$r;
        }
            
        return $return;
    }
}
?>