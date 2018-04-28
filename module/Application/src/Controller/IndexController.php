<?php
namespace Application\Controller;

use Application\Model\NoeudXml;
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
        $connected = $this->_authManager->isConnected();

        if($connected){
            if($fav == 1 || $this->_tableFavoris->isInFavorisOfUser($id, $this->_authManager->getUserData()['id'])){
                $boutonFavorisActif = false;
            }
        }
        

        $fiche = $this->_tableFiche->find($id);

        return new ViewModel([
            'fiche' => $fiche,
            'boutonFavorisActif' => $boutonFavorisActif,
            'connected' => $connected,
            'fav' => $fav,
        ]);
    }

    // téléchargement d'une fiche spécifique
    public function telechargeAction() {

        $id = (int)$this->params()->fromRoute('id', -1);

        $fiche = $this->_tableFiche->find($id);

        $noeud = $fiche->toXML();

        $xml= new Xml();
        $res = $xml->createXML($noeud);

        $fileName = str_replace(" ", "_", $fiche->_nom) . ".xml";

        $res->save($fileName);

        $response = new \Zend\Http\Response\Stream();
        $response->setStream(fopen($fileName, 'r'));
        $response->setStatusCode(200);

        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Type', 'text/xml')
                ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $fileName . '"')
                ->addHeaderLine('Content-Length', filesize($fileName));

        $response->setHeaders($headers);
        return $response;
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