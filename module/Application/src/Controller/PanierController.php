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

    public function addpanierAction() {

        $redirectUrl = (string)$this->params()->fromQuery('redirectUrl', '');
        if (strlen($redirectUrl)>2048) {
            throw new \Exception("Too long redirectUrl argument passed");
        }

        $idProduct = (int)$this->params()->fromRoute('id', -1);

        $panier = new Panier();
        $panier->_idUser = $this->_authManager->getUserData()['id'];
        $panier->_idProduct = $idProduct;

        $this->_tablePanier->insert($panier);

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

    public function panierAction() {

        $idProductRemoveFromPanier = (string)$this->params()->fromQuery('idProductRemoveFromPanier', '');

        $productRemove = '';

        $products = $this->_tablePanier->find($this->_authManager->getUserData()['id']);

        $prixHT = 0;

        foreach( $products as $p )
            $prixHT += $p->_prix;

        $taxe = $this->_tableMetadata->findByNom('taxe')->_valeur;

        $prixTTC = $prixHT + $prixHT * ($taxe / 100);

        if(!empty($idProductRemoveFromPanier)){
            $productRemove = $this->_tableProduct->find($idProductRemoveFromPanier);
        }

        return new ViewModel([
            'products' => $products,
            'productRemove' => $productRemove,
            'taxe' => $taxe,
            'prixHT' => $prixHT,
            'prixTTC' => $prixTTC,
        ]);
    }

    public function removepanierAction() {

        $idProduct = (int)$this->params()->fromRoute('id', -1);

        $redirectUrl = "/panier?idProductRemoveFromPanier=" . $idProduct;

        $idProduct = (int)$this->params()->fromRoute('id', -1);

        $idUser = $this->_authManager->getUserData()['id'];

        $this->_tablePanier->delete($idUser, $idProduct);

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

    public function payerAction() {

        $products = $this->_tablePanier->find($this->_authManager->getUserData()['id']);

        $prixTotal = 0;

        foreach( $products as $p )
            $prixTotal += $p->_prix;
        
        $taxe = $this->_tableMetadata->findByNom('taxe')->_valeur;

        $prixTotal = $prixTotal + $prixTotal * ($taxe / 100);

        $username = $this->_authManager->getUserData()['username'];

        $isError = false;

        $form = new PaymentForm();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            
            $form->setData($data);
            
            $succes = (float)rand()/(float)getrandmax();

            $isError = $succes >= 0.5;

            if(!$isError){

                return $this->forward()->dispatch('Application\Controller\PanierController', ['action' => "infopaiement"]);
            }
            
        } else {
            $form->setData(['username'=>$username]);
        }

        return new ViewModel([
            'prixTotal' => $prixTotal,
            'form' => $form,
            'isError' => $isError
        ]);
    }

    public function infopaiementAction(){

        $data = $this->params()->fromPost();
        $idTransaction = $this->genererIDTransaction();

        $products = $this->_tablePanier->find($this->_authManager->getUserData()['id']);

        $prixTotal = 0;

        foreach( $products as $p )
            $prixTotal += $p->_prix;

        $taxe = $this->_tableMetadata->findByNom('taxe')->_valeur;

        $prixTotal = $prixTotal + $prixTotal * ($taxe / 100);

        $this->_tableHistorique->insertAllToHistorique($this->_authManager->getUserData()['id'], $products);

        $this->_tablePanier->deleteAllPanierOfUser($this->_authManager->getUserData()['id']);

        return new ViewModel([
            'prixTotal' => $prixTotal,
            'idTransaction' => $idTransaction,
            'data' => $data,
        ]);
    }

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