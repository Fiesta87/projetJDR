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
    
    /**
     * Constructs the service.
     */
    public function __construct($authService, $urlHelper)
    {
        $this->authService = $authService;
        $this->urlHelper = $urlHelper;
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
            'label' => 'Galerie',
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

            $items[] = [
                'id' => 'création',
                'label' => 'Création de compte',
                'link' => $url('creation')
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
                        'id' => 'compte',
                        'label' => 'Mon compte',
                        'link' => $url('compte')
                    ],
                ],
            ];

            $items[] = [
                'id' => 'admin',
                'label' => 'Mes Fiches',
                'link' => $url('admin')
            ];

            $items[] = [
                'id' => 'favoris',
                'label' => 'Mes Favoris',
                'link' => $url('favoris')
            ];

        }
        
        return $items;
    }
}


