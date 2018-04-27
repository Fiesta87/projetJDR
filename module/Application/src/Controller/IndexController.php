<?php
namespace Application\Controller;

use Application\Model\NoeudXml;
use Application\Model\AttributXML;
use Application\Model\Xml;
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
/*
        $noeud = array();
        $noeud[0]=new NoeudXml();
        $noeud[0]->name="fichePersoType";

        $noeudChild = array();
        $noeudChild[0]=new NoeudXml();
        $noeudChild[0]->name="Attribut";

        $noeudChildChild = array();
        $noeudChildChild[0]=new NoeudXml();
        $noeudChildChild[1]=new NoeudXml();
        $noeudChildChild[2]=new NoeudXml();

        $noeudChildChild[0]->name="Physique";
        $noeudChildChild[0]->value="200";
        $noeudChildChild[0]->attribute['name'] ="value";
        $noeudChildChild[0]->attribute['value'] ="1";

        $noeudChildChild[1]->name="Mental";
        $noeudChildChild[1]->value="300";
        $noeudChildChild[1]->attribute['name'] ="value";
        $noeudChildChild[1]->attribute['value'] ="1";

        $noeudChildChild[2]->name="Social";
        $noeudChildChild[2]->value="10";
        $noeudChildChild[2]->attribute['name'] ="value";
        $noeudChildChild[2]->attribute['value']  ="1";



        $noeudChild[0]->child=$noeudChildChild;
        $noeud[0]->child=$noeudChild;





        print_r($noeud);

        $xml= new Xml();
        $res = $xml->createXML($noeud);

        $res->save("test.xml");*/




        $page = (int)$this->params()->fromRoute('page', -1);

        // les pages commencent à 1
        $pagePrecedente = max($page-1, 1);
        $pageSuivante = $page+1;

        $fiches = [];

        if ($this->getRequest()->isPost()) {

            $data = $this->params()->fromPost();
            $fiches = $this->_tableFiche->fetchPageRecherche($page, $data['recherche']);

        } else {

            $fiches = $this->_tableFiche->fetchPage($page);
        }

        return new ViewModel([
            'fiches' => $fiches,
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