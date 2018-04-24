<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Application\Model\Fiche;
use Application\Services\FicheTable;
use Application\Model\Metadata;
use Application\Services\MetadataTable;
use Application\Form\FicheAddForm;
use Zend\View\Model\ViewModel;
use User\Services\AuthManager;
use Zend\Uri\UriFactory;
use Zend\Mvc\Controller\Plugin\Forward;

class AdminController extends AbstractActionController
{
    private $_authManager;
    private $_tableFiche;
    private $_tableMetadata;

    public function __construct(AuthManager $authManager, FicheTable $tableFiche, MetadataTable $tableMetadata)
    {
        $this->_authManager = $authManager;
        $this->_tableFiche = $tableFiche;
        $this->_tableMetadata = $tableMetadata;
    }

    // liste les produits du catalogue pour leur management
    public function adminAction() {

        // si on vient sur cette page après avoir ajouté/modifié/supprimé un article,
        // on récupère son nom pour réaliser un affichage de confirmation

        // $nameProductDeleted = (string)$this->params()->fromQuery('nameProductDeleted', '');

        // $nameProductAdded = (string)$this->params()->fromQuery('nameProductAdded', '');

        // $nameProductModified = (string)$this->params()->fromQuery('nameProductModified', '');

        // les pages commencent à 1

        return new ViewModel([
            'fiches' => $this->_tableFiche->getFichesOfUser($this->_authManager->getUserData()['id']),
            // 'nameProductDeleted' => $nameProductDeleted,
            // 'nameProductAdded' => $nameProductAdded,
            // 'nameProductModified' => $nameProductModified,
        ]);
    }
/*
    // supprime un produit
    public function deleteAction() {

        $idProduct = (int)$this->params()->fromRoute('id', -1);

        $page = (string)$this->params()->fromQuery('page', '1');

        $productDeleted = $this->_tableProduct->find($idProduct);

        // si le produit à supprimer n'existe pas -> 404
        if ($productDeleted == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // on spécifit dans l'URL de redirection le nom du produit qui a été supprimé
        $redirectUrl = "/admin/" . $page . "?nameProductDeleted=" . $productDeleted->_nom;

        // suppression du produit et de ses références dans les paniers et historiques des utilisateurs
        $this->_tableProduct->delete($productDeleted);
        $this->_tablePanier->deleteProduct($idProduct);
        $this->_tableHistorique->delete($idProduct);

        // on redirige vers la liste avec un affichage pour confirmer la suppression
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
    
    // modifit une fiche
    public function editficheAction() {

        $id = (int)$this->params()->fromRoute('id', -1);

        // si l'id spécifié est négatif ou n'exite pas -> 404
        if ($id<1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        $product = $this->_tableProduct->find($id);
        
        if ($product == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $form = new ProductEditForm();
        
        // si on a complété le formulaire
        if ($this->getRequest()->isPost()) {

            $data = $this->params()->fromPost();

            $form->setData($data);
            
            if(!$form->isValid()) {

                return new ViewModel(array(
                    'product' => $product,
                    'form' => $form,
                    'error' => true,
                ));
            }

            $update =[
                'nom' => $data['nom'],
                'prix' => $data['prix'],
                'description' => $data['description']
            ];

            // on réalise la mise à jour de l'article
            $this->_tableProduct->update($product, $update);

            // on redirige vers la liste des articles en affichant un message de confirmation

            $redirectUrl = "/admin?nameProductModified=" . $data['nom'];
            
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

        // sinon on remplit le formulaire avec les infos du produit et on l'affiche
        } else {
            $form->setData([
                'nom'=>$product->_nom,
                'prix'=>$product->_prix,
                'description'=>$product->_description
            ]);

            return new ViewModel(array(
                'product' => $product,
                'form' => $form,
                'error' => false,
            ));
        }
    }
    */
    // ajoute une fiche
    public function addAction() 
    {
        $form = new FicheAddForm();

        // si on a complété le formulaire
        if ($this->getRequest()->isPost()) {

            $data = $this->params()->fromPost();

            $form->setData($data);

            if(!$form->isValid()) {

                return new ViewModel(array(
                    'form' => $form,
                    'error' => true,
                ));
            }

            // on crée la nouvelle fiche

            $fiche = new Fiche();

            $fiche->_nom = $data['nom'];
            $fiche->_description = $data['description'];
            $fiche->_idUser = $this->_authManager->getUserData()['id'];

            // on ajoute la fiche en BD
            $id = $this->_tableFiche->insert($fiche);

            // on redirige vers la lmodification

            $redirectUrl = "/editfiche/" . $id;
            
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

        // sinon on crée un formulaire vide et on l'affiche
        } else {

            return new ViewModel(array(
                'form' => $form,
                'error' => false,
            ));
        }
    }
}

?>