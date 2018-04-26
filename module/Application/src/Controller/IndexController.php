<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Application\Model\Fiche;
use Application\Services\FicheTable;
use Application\Model\Metadata;
use Application\Services\MetadataTable;
use Application\Services\FavorisTable;
use Application\Model\Favoris;
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
            'favoris' => $this->_tableFavoris->get5MostFavorite(),
            'page' => $page,
            'pagePrecedente' => $pagePrecedente,
            'pageSuivante' => $pageSuivante,
        ]);
    }

    // affichage d'une fiche spécifique
    public function ficheAction() {

        $id = (int)$this->params()->fromRoute('id', -1);

        $fav = (int)$this->params()->fromQuery('fav', 0);

        $boutonFavorisActif = true;

        if($fav == 1 || $this->_tableFavoris->isInFavorisOfUser($id, $this->_authManager->getUserData()['id'])){
            $boutonFavorisActif = false;
        }

        return new ViewModel([
            'fiche' => $this->_tableFiche->find($id),
            'boutonFavorisActif' => $boutonFavorisActif,
            'fav' => $fav,
        ]);
    }

    //favoris
    public function favorisAction() {

        $idUser = $this->_authManager->getUserData()['id'];

        return new ViewModel([
            'fiches' => $this->_tableFavoris->getFichesFavorisOfUser($idUser),
        ]);
    }

    // ajout aux favoris
    public function addfavorisAction() {

        $f = new Favoris();

        $f->_idUser = $this->_authManager->getUserData()['id'];

        $f->_idFiche = (int)$this->params()->fromRoute('id', -1);

        $this->_tableFavoris->insert($f);

        // on spécifit dans l'URL de redirection que l'on a ajouté un favoris
        $redirectUrl = "/fiche/" . $f->_idFiche . "?fav=1";

        $uri = UriFactory::factory($redirectUrl);
        if (!$uri->isValid() || $uri->getHost()!=null){
            throw new \Exception('Incorrect redirect URL: ' . $redirectUrl);
        }
        $this->redirect()->toUrl($redirectUrl);
    }

    // suppression des favoris
    public function removefavorisAction() {

        $f = new Favoris();

        $f->_idUser = $this->_authManager->getUserData()['id'];

        $f->_idFiche = (int)$this->params()->fromRoute('id', -1);

        $this->_tableFavoris->delete($f);

        // on spécifit dans l'URL de redirection que l'on a supprimé un favoris
        $redirectUrl = "/fiche/" . $f->_idFiche . "?fav=2";

        $uri = UriFactory::factory($redirectUrl);
        if (!$uri->isValid() || $uri->getHost()!=null){
            throw new \Exception('Incorrect redirect URL: ' . $redirectUrl);
        }
        $this->redirect()->toUrl($redirectUrl);
    }
}

?>