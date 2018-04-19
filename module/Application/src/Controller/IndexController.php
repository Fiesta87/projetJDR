<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Application\Model\Fiche;
use Application\Services\FicheTable;
use Application\Model\Metadata;
use Application\Services\MetadataTable;
use Application\Services\FavorisTable;
use Zend\View\Model\ViewModel;
use User\Services\AuthManager;
use Zend\Uri\UriFactory;

class IndexController extends AbstractActionController
{
    private $_authManager;
    private $_tableFiche;
    private $_tableMetadata;
    private $_tableFavoris;

    public function __construct(AuthManager $authManager, FicheTable $tableFiche, MetadataTable $tableMetadata, FavorisTable $tableFavoris)
    {
        $this->_authManager = $authManager;
        $this->_tableFiche = $tableFiche;
        $this->_tableMetadata = $tableMetadata;
        $this->_tableFavoris = $tableFavoris;
    }

    // liste des fiche de la galerie
    public function indexAction() {

        $page = (int)$this->params()->fromRoute('page', -1);

        // les pages commencent à 1
        $pagePrecedente = max($page-1, 1);
        $pageSuivante = $page+1;

        return new ViewModel([
            'fiches' => $this->_tableFiche->fetchPage($page),
            'page' => $page,
            'pagePrecedente' => $pagePrecedente,
            'pageSuivante' => $pageSuivante,
        ]);
    }

    // affichage d'une fiche spécifique
    public function ficheAction() {

        $id = (int)$this->params()->fromRoute('id', -1);

        return new ViewModel([
            'fiche' => $this->_tableFiche->find($id),
        ]);
    }

    //favoris
    public function favorisAction() {

        $idUser = $this->_authManager->getUserData()['id'];

        return new ViewModel([
            'fiches' => $this->_tableFavoris->getFichesFavorisOfUser($idUser),
        ]);
    }
}

?>