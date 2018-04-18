<?php
namespace Application\Services;

/**
 * This service is responsible for determining which items should be in the main menu.
 * The items may be different depending on whether the user is authenticated or not.
 */
class NavManager
{
    /**
     * Auth service.
     * @var Zend\Authentication\Authentication
     */
    private $authService;
    
    /**
     * Url view helper.
     * @var Zend\View\Helper\Url
     */
    private $urlHelper;

    private $_tableUserprivilege;
    
    /**
     * Constructs the service.
     */
    public function __construct($authService, $urlHelper, UserprivilegeTable $tableUserprivilege)
    {
        $this->authService = $authService;
        $this->urlHelper = $urlHelper;
        $this->_tableUserprivilege = $tableUserprivilege;
    }
    
    /**
     * This method returns menu items depending on whether user has logged in or not.
     */
    public function getMenuItems() 
    {
        $url = $this->urlHelper;
        $items = [];
        
        $items[] = [
            'id' => 'home',
            'label' => 'Catalogue',
            'link'  => $url('index')
        ];
        
        // Display "Login" menu item for not authorized user only. On the other hand,
        // display "Admin" and "Logout" menu items only for authorized users.
        if (!$this->authService->hasIdentity()) {
            $items[] = [
                'id' => 'login',
                'label' => 'Connexion',
                'link'  => $url('login')
            ];
        } else {
            $items[] = [
                'id' => 'logout',
                'label' => $this->authService->getIdentity()['username'],
                'dropdown' => [
                    [
                        'id' => 'logout',
                        'label' => 'Déconnexion',
                        'link' => $url('logout')
                    ],
                    [
                        'id' => 'historique',
                        'label' => 'Historique des achats',
                        'link' => $url('historique')
                    ],
                    [
                        'id' => 'compte',
                        'label' => 'Mon compte',
                        'link' => $url('compte')
                    ],
                ],
            ];

            $items[] = [
                'id' => 'panier',
                'label' => 'Mon Panier',
                'link' => $url('panier')
            ];

            if($this->_tableUserprivilege->isAdmin($this->authService->getIdentity()['id'])){
                $items[] = [
                    'id' => 'admin',
                    'label' => 'Administration',
                    'link' => $url('admin')
                ];
            }
        }
        
        return $items;
    }
}


