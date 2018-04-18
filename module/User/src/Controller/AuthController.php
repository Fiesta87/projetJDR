<?php
namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Authentication\Result;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use User\Services\UserManager;
use User\Services\AuthManager;
use User\Form\LoginForm;
use User\Form\UserForm;
use Zend\Uri\UriFactory;

class AuthController extends AbstractActionController
{
    private $_userManager;
    private $_authManager;

    public function __construct(UserManager $userManager, AuthManager $authManager)
    {
        $this->_userManager = $userManager;
        $this->_authManager = $authManager;
    }

    // affichage et traitement de la réponse du formulaire pour connecter un utilisateur
    public function loginAction() {
        $redirectUrl = (string)$this->params()->fromQuery('redirectUrl', '');
        if (strlen($redirectUrl)>2048) {
            throw new \Exception("Too long redirectUrl argument passed");
        }

        $form = new LoginForm();
        $form->get('redirect_url')->setValue($redirectUrl);

        $isLoginError = false;

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            
            if($form->isValid()) {
                $data = $form->getData();
                $result = $this->_authManager->login($data['useremail'], $data['password']);

                if ($result->getCode() == Result::SUCCESS) {
                    $redirectUrl = $this->params()->fromPost('redirect_url', '');

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
                } else {
                    $isLoginError = true;
                }
            } else {
                $isLoginError = true;
            }
        }

        return new ViewModel([
            'form' => $form,
            'isLoginError' => $isLoginError,
            'redirectUrl' => $redirectUrl
        ]);
    }

    // deconnecte un utilisateur et redirige sur la page de login
    public function logoutAction() {
        $this->_authManager->logout();

        return $this->redirect()->toRoute('login');
    }

    // affiche le compte de l'utilisateur pour modifier des paramètres personnels
    public function compteAction() {

        $form = new UserForm();

        $modif = false;

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            
            $form->setData($data);

            $update = [
                'email' => $data['email']
            ];

            $this->_userManager->update($this->_authManager->getUserData()['id'], $update);
            
            $this->_authManager->updateUserEmailOnSession($data['email'], $data['password']);
            // $this->_authManager->getUserData()['email'] = $data['email'];

            $modif = true;
        } else {
            $form->setData(['email'=>$this->_authManager->getUserData()['email']]);
        }

        return new ViewModel([
            'user' => $this->_authManager->getUserData(),
            'form' => $form,
            'modif' => $modif,
        ]);
    }
}

?>