<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Application\Model\Product;
use Application\Services\ProductTable;
use Application\Model\Panier;
use Application\Services\PanierTable;
use Application\Model\Metadata;
use Application\Services\MetadataTable;
use Application\Model\Historique;
use Application\Services\HistoriqueTable;
use Application\Form\ProductEditForm;
use Application\Form\PaymentForm;
use Zend\View\Model\ViewModel;
use User\Services\AuthManager;
use Zend\Uri\UriFactory;
use Zend\Mvc\Controller\Plugin\Forward;

class PanierController extends AbstractActionController
{
    private $_authManager;
    private $_tableProduct;
    private $_tablePanier;
    private $_tableMetadata;
    private $_tableHistorique;

    public function __construct(AuthManager $authManager, ProductTable $tableProduct, PanierTable $tablePanier, MetadataTable $tableMetadata, HistoriqueTable $tableHistorique)
    {
        $this->_authManager = $authManager;
        $this->_tableProduct = $tableProduct;
        $this->_tablePanier = $tablePanier;
        $this->_tableMetadata = $tableMetadata;
        $this->_tableHistorique = $tableHistorique;
    }

    // ajoute le produit spécifié par l'URL au panier puis redirige vers la page précédente avec un message de confirmation
    public function addpanierAction() {

        $redirectUrl = (string)$this->params()->fromQuery('redirectUrl', '');
        if (strlen($redirectUrl)>2048) {
            throw new \Exception("Too long redirectUrl argument passed");
        }

        $idProduct = (int)$this->params()->fromRoute('id', -1);

        // nouvelle entré dans le panier de l'utilisateur

        $panier = new Panier();
        $panier->_idUser = $this->_authManager->getUserData()['id'];
        $panier->_idProduct = $idProduct;

        // ajout le panier en BD

        $this->_tablePanier->insert($panier);

        // on redirige sur la page spécifié par l'URL

        if (!empty($redirectUrl)) {
            $uri = UriFactory::factory($redirectUrl);
            if (!$uri->isValid() || $uri->getHost()!=null)
                throw new \Exception('Incorrect redirect URL: ' . $redirectUrl);
        }

        if(empty($redirectUrl)) {
            return $this->redirect()->toRoute('index');
        } else {
            $this->redirect()->toUrl($redirectUrl);
        }
    }

    // affiche le panier de l'utilisateur
    public function panierAction() {

        // si on vient sur cette page après avoir ajouté supprimer un article du panier,
        // on le récupère pour réaliser un affichage de confirmation

        $idProductRemoveFromPanier = (string)$this->params()->fromQuery('idProductRemoveFromPanier', '');

        $productRemove = '';

        if(!empty($idProductRemoveFromPanier)){
            $productRemove = $this->_tableProduct->find($idProductRemoveFromPanier);
        }

        // la liste des produits du panier
        $products = $this->_tablePanier->find($this->_authManager->getUserData()['id']);

        // calcul des prix HT et TTC

        $prixHT = 0;

        foreach( $products as $p )
            $prixHT += $p->_prix;

        $taxe = $this->_tableMetadata->findByNom('taxe')->_valeur;

        $prixTTC = $prixHT + $prixHT * ($taxe / 100);

        return new ViewModel([
            'products' => $products,
            'productRemove' => $productRemove,
            'taxe' => $taxe,
            'prixHT' => $prixHT,
            'prixTTC' => $prixTTC,
        ]);
    }

    // supprime un produit du panier
    public function removepanierAction() {

        // on supprime le produit du panier de l'utilisateur connecté

        $idProduct = (int)$this->params()->fromRoute('id', -1);

        $idUser = $this->_authManager->getUserData()['id'];

        $this->_tablePanier->delete($idUser, $idProduct);

        // on redirige vers la liste des articles du panier en affichant un message de confirmation

        $redirectUrl = "/panier?idProductRemoveFromPanier=" . $idProduct;

        if (!empty($redirectUrl)) {
            $uri = UriFactory::factory($redirectUrl);
            if (!$uri->isValid() || $uri->getHost()!=null)
                throw new \Exception('Incorrect redirect URL: ' . $redirectUrl);
        }

        if(empty($redirectUrl)) {
            return $this->redirect()->toRoute('index');
        } else {
            $this->redirect()->toUrl($redirectUrl);
        }
    }

    // affiche le formulaire de paiement du panier et traite également le retour après la saisie de l'utilisateur
    public function payerAction() {

        // la liste des produits du panier
        $products = $this->_tablePanier->find($this->_authManager->getUserData()['id']);

        // calcul des prix HT et TTC

        $prixTotal = 0;

        foreach( $products as $p )
            $prixTotal += $p->_prix;
        
        $taxe = $this->_tableMetadata->findByNom('taxe')->_valeur;

        $prixTotal = $prixTotal + $prixTotal * ($taxe / 100);

        $username = $this->_authManager->getUserData()['username'];

        $isError = false;

        $form = new PaymentForm();

        // si on a complété le formulaire
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            
            $form->setData($data);
            
            // on détermine si c'est un succes ou non
            $succes = (float)rand()/(float)getrandmax();

            $isError = $succes >= 0.5;

            // si le paiement a réussit
            if(!$isError){
                // on redirige toute la requette vers l'affichage du paiement
                return $this->forward()->dispatch('Application\Controller\PanierController', ['action' => "infopaiement"]);
            }
            
        } else {
            // on remplit une partie du formulaire
            $form->setData(['username'=>$username]);
        }

        return new ViewModel([
            'prixTotal' => $prixTotal,
            'form' => $form,
            'isError' => $isError
        ]);
    }

    // affiche les informations d'un paiement fructueux
    public function infopaiementAction(){

        // on récupère les données saisies
        $data = $this->params()->fromPost();
        $idTransaction = $this->genererIDTransaction();

        // la liste des produits du panier
        $products = $this->_tablePanier->find($this->_authManager->getUserData()['id']);

        // calcul des prix HT et TTC

        $prixTotal = 0;

        foreach( $products as $p )
            $prixTotal += $p->_prix;

        $taxe = $this->_tableMetadata->findByNom('taxe')->_valeur;

        $prixTotal = $prixTotal + $prixTotal * ($taxe / 100);

        // le panier passe dans l'historique des achats
        $this->_tableHistorique->insertAllToHistorique($this->_authManager->getUserData()['id'], $products);

        // on vide le panier
        $this->_tablePanier->deleteAllPanierOfUser($this->_authManager->getUserData()['id']);

        return new ViewModel([
            'prixTotal' => $prixTotal,
            'idTransaction' => $idTransaction,
            'data' => $data,
        ]);
    }

    // crée un ID de transaction de 5 lettres puis 10 chiffres
    private function genererIDTransaction(){
        $lettres = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $chiffres = "0123456789";

        $result = "";

        for ($i=0; $i<5; $i++) {
            $result = $result . $lettres[random_int(0, strlen($lettres) - 1)];
        }

        for ($i=0; $i<10; $i++) {
            $result = $result . $chiffres[random_int(0, strlen($chiffres) - 1)];
        }

        return $result;
    }
}

?>