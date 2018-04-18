<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Application\Model\Product;
use Application\Services\ProductTable;
use Application\Model\Metadata;
use Application\Services\MetadataTable;
use Application\Model\Panier;
use Application\Services\PanierTable;
use Application\Model\Historique;
use Application\Services\HistoriqueTable;
use Application\Form\ProductEditForm;
use Zend\View\Model\ViewModel;
use User\Services\AuthManager;
use Zend\Uri\UriFactory;
use Zend\Mvc\Controller\Plugin\Forward;

class AdminController extends AbstractActionController
{
    private $_authManager;
    private $_tableProduct;
    private $_tableMetadata;
    private $_tablePanier;
    private $_tableHistorique;

    public function __construct(AuthManager $authManager, ProductTable $tableProduct, MetadataTable $tableMetadata, PanierTable $tablePanier, HistoriqueTable $tableHistorique)
    {
        $this->_authManager = $authManager;
        $this->_tableProduct = $tableProduct;
        $this->_tableMetadata = $tableMetadata;
        $this->_tablePanier = $tablePanier;
        $this->_tableHistorique = $tableHistorique;
    }

    public function adminAction() {

        $page = (int)$this->params()->fromRoute('page', -1);

        $nameProductDeleted = (string)$this->params()->fromQuery('nameProductDeleted', '');

        $nameProductAdded = (string)$this->params()->fromQuery('nameProductAdded', '');

        $nameProductModified = (string)$this->params()->fromQuery('nameProductModified', '');

        $pagePrecedente = max($page-1, 1);
        $pageSuivante = $page+1;

        return new ViewModel([
            'products' => $this->_tableProduct->fetchPage($page),
            'page' => $page,
            'pagePrecedente' => $pagePrecedente,
            'pageSuivante' => $pageSuivante,
            'nameProductDeleted' => $nameProductDeleted,
            'nameProductAdded' => $nameProductAdded,
            'nameProductModified' => $nameProductModified,
        ]);
    }

    public function deleteAction() {

        $idProduct = (int)$this->params()->fromRoute('id', -1);

        $page = (string)$this->params()->fromQuery('page', '1');

        $productDeleted = $this->_tableProduct->find($idProduct);

        if ($productDeleted == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $redirectUrl = "/admin/" . $page . "?nameProductDeleted=" . $productDeleted->_nom;

        $this->_tableProduct->delete($productDeleted);
        $this->_tablePanier->deleteProduct($idProduct);
        $this->_tableHistorique->delete($idProduct);

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
    
    public function editAction() 
    {
        $id = (int)$this->params()->fromRoute('id', -1);
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

            $this->_tableProduct->update($product, $update);

            $redirectUrl = "/admin?nameProductModified=" . $data['nom'];
            
            $this->redirect()->toUrl($redirectUrl);
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
    
    public function addAction() 
    {
        $form = new ProductEditForm();

        $image = "/img/img" . random_int(12, 21) . ".jpg";
        
        if ($this->getRequest()->isPost()) {

            $data = $this->params()->fromPost();

            $form->setData($data);

            if(!$form->isValid()) {

                return new ViewModel(array(
                    'image' => $data['image'],
                    'form' => $form,
                    'error' => true,
                ));
            }

            $product = new Product();

            $product->_nom = $data['nom'];
            $product->_prix = $data['prix'];
            $product->_description = $data['description'];
            $product->_image = $data['image'];

            $this->_tableProduct->insert($product, $update);

            $redirectUrl = "/admin?nameProductAdded=" . $data['nom'];
            
            $this->redirect()->toUrl($redirectUrl);
        } else {
            $form->setData([
                'image'=> $image,
            ]);

            return new ViewModel(array(
                'image' => $image,
                'form' => $form,
                'error' => false,
            ));
        }
    }
}

?>