<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Application\Model\Fiche;
use Application\Model\Attribut;
use Application\Services\FicheTable;
use Application\Services\AttributTable;
use Application\Model\Metadata;
use Application\Services\MetadataTable;
use Application\Form\FicheAddForm;
use Application\Form\AttributAddForm;
use Zend\View\Model\ViewModel;
use User\Services\AuthManager;
use Zend\Uri\UriFactory;
use Zend\Mvc\Controller\Plugin\Forward;

class AdminController extends AbstractActionController
{
    private $_authManager;
    private $_tableFiche;
    private $_tableMetadata;
    private $_tableAttribut;

    public function __construct(AuthManager $authManager, FicheTable $tableFiche, MetadataTable $tableMetadata, AttributTable $tableAttribut)
    {
        $this->_authManager = $authManager;
        $this->_tableFiche = $tableFiche;
        $this->_tableMetadata = $tableMetadata;
        $this->_tableAttribut = $tableAttribut;
    }

    // liste les produits du catalogue pour leur management
    public function adminAction() {

        // si on vient sur cette page après avoir ajouté/modifié/supprimé un article,
        // on récupère son nom pour réaliser un affichage de confirmation

        // $nameProductDeleted = (string)$this->params()->fromQuery('nameProductDeleted', '');

        // les pages commencent à 1

        return new ViewModel([
            'fiches' => $this->_tableFiche->getFichesOfUser($this->_authManager->getUserData()['id']),
            // 'nameProductDeleted' => $nameProductDeleted,
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
    */
    // modifit une fiche
    public function editficheAction() {

        $id = (int)$this->params()->fromRoute('id', -1);

        // si l'id spécifié est négatif ou n'exite pas -> 404
        if ($id<1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        $fiche = $this->_tableFiche->find($id);
        
        if ($fiche == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $form = new FicheAddForm();
        
        // si on a complété le formulaire
        if ($this->getRequest()->isPost()) {

            $data = $this->params()->fromPost();

            $form->setData($data);
            
            if(!$form->isValid()) {

                return new ViewModel(array(
                    'fiche' => $fiche,
                    'form' => $form,
                    'error' => true,
                    'modif' => false,
                ));
            }

            $update =[
                'nom' => $data['nom'],
                'description' => $data['description']
            ];

            // on réalise la mise à jour de la fiche
            $this->_tableFiche->update($fiche, $update);

            $fiche = $this->_tableFiche->find($id);

            return new ViewModel(array(
                'fiche' => $fiche,
                'form' => $form,
                'error' => false,
                'modif' => true,
            ));

        // sinon on remplit le formulaire avec les infos de la fiche et on l'affiche
        } else {
            $form->setData([
                'nom'=>$fiche->_nom,
                'description'=>$fiche->_description
            ]);

            return new ViewModel(array(
                'fiche' => $fiche,
                'form' => $form,
                'error' => false,
                'modif' => false,
            ));
        }
    }

    // ajoute un attribut à une fiche
    public function addattributAction() {

        $idfiche = (int)$this->params()->fromRoute('idfiche', -1);
        $idattributparent = (int)$this->params()->fromRoute('idattributparent', -1);

        // si l'idfiche spécifié est négatif ou n'exite pas -> 404
        if ($idfiche<1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        $fiche = $this->_tableFiche->find($idfiche);
        
        if ($fiche == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $form = new AttributAddForm();
        
        // si on a complété le formulaire
        if ($this->getRequest()->isPost()) {

            $data = $this->params()->fromPost();

            $form->setData($data);
            
            if(!$form->isValid()) {

                $nomAttributParent = "NULL_NAME";

                if($idattributparent != -1){
                    $nomAttributParent = $this->_tableAttribut->find($idattributparent)->_nom;
                }
                
                return new ViewModel(array(
                    'fiche' => $fiche,
                    'nomAttributParent' => $nomAttributParent,
                    'form' => $form,
                    'error' => true,
                ));
            }

            // on crée le nouvel attribut

            $attribut = new Attribut();

            $attribut->_nom = $data['nom'];
            $attribut->_idFiche = $idfiche;
            $attribut->_idAttributParent = $idattributparent;
            $attribut->_valeur = 1;

            // on ajoute la fiche en BD
            $this->_tableAttribut->insert($attribut);

            // on redirige vers la modification

            $redirectUrl = "/editfiche/" . $idfiche;
            
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
            
        // sinon on remplit le formulaire avec les infos de l'attribut et on l'affiche
        } else {

            $nomAttributParent = "NULL_NAME";

            if($idattributparent != -1){
                $nomAttributParent =$this->_tableAttribut->find($idattributparent)->_nom;
            }
            
            return new ViewModel(array(
                'fiche' => $fiche,
                'nomAttributParent' => $nomAttributParent,
                'form' => $form,
                'error' => false,
            ));
        }
    }
    
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

            // on redirige vers la modification

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