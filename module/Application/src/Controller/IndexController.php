<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Application\Model\Product;
use Application\Services\ProductTable;
use Application\Model\Metadata;
use Application\Services\MetadataTable;
use Application\Model\Historique;
use Application\Services\HistoriqueTable;
use Zend\View\Model\ViewModel;
use User\Services\AuthManager;
use Zend\Uri\UriFactory;

class IndexController extends AbstractActionController
{
    private $_authManager;
    private $_tableProduct;
    private $_tableMetadata;
    private $_tableHistorique;

    public function __construct(AuthManager $authManager, ProductTable $tableProduct, MetadataTable $tableMetadata, HistoriqueTable $tableHistorique)
    {
        $this->_authManager = $authManager;
        $this->_tableProduct = $tableProduct;
        $this->_tableMetadata = $tableMetadata;
        $this->_tableHistorique = $tableHistorique;
    }

    // liste des produits du catalogue
    public function indexAction() {

        $page = (int)$this->params()->fromRoute('page', -1);

        // si on vient sur cette page après avoir ajouté un article au panier,
        // on le récupère pour réaliser un affichage de confirmation

        $idProductAddToPanier = (string)$this->params()->fromQuery('idProductAddToPanier', '');

        $productPanier = '';

        if(!empty($idProductAddToPanier)){
            $productPanier = $this->_tableProduct->find($idProductAddToPanier);
        }

        // les pages commencent à 1
        $pagePrecedente = max($page-1, 1);
        $pageSuivante = $page+1;

        return new ViewModel([
            'products' => $this->_tableProduct->fetchPage($page),
            'page' => $page,
            'pagePrecedente' => $pagePrecedente,
            'pageSuivante' => $pageSuivante,
            'productPanier' => $productPanier,
        ]);
    }

    // affichage d'un produit spécifique
    public function produitAction() {

        $id = (int)$this->params()->fromRoute('id', -1);

        // si on vient sur cette page après avoir ajouté un article au panier,
        // on le récupère pour réaliser un affichage de confirmation
        
        $idProductAddToPanier = (string)$this->params()->fromQuery('idProductAddToPanier', '');

        $productPanier = '';

        if(!empty($idProductAddToPanier)){
            $productPanier = $this->_tableProduct->find($idProductAddToPanier);
        }

        return new ViewModel([
            'product' => $this->_tableProduct->find($id),
            'productPanier' => $productPanier,
        ]);
    }

    // affichage de l'historique des achats d'un utilisateur
    public function historiqueAction() {

        return new ViewModel([
            'products' => $this->_tableHistorique->find($this->_authManager->getUserData()['id']),
        ]);
    }
}

?>