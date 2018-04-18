<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

return [
    'router' => [
        'routes' => [

            /*  ---------------- IndexController ---------------- */

            // liste des produits du catalogue
            'index' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/[page/:page]',
                    'constraints' => [
                        'page' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'index',
                        'page'        => 1,
                    ],
                ],
            ],

            // affichage d'un produit spécifique
            'produit' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/produit/:id',
                    'constraints' => [
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'produit',
                    ],
                ],
            ],

            // affichage de l'historique des achats d'un utilisateur
            'historique' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/historique',
                    'defaults' => [
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'historique',
                    ],
                ],
            ],
/*
            'compte' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/compte',
                    'defaults' => [
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'compte',
                    ],
                ],
            ],*/

            /*  ---------------- PanierController ---------------- */

            // ajoute le produit spécifié par l'URL au panier puis redirige vers la page précédente avec un message de confirmation
            'addpanier' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/addpanier/:id',
                    'constraints' => [
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller'    => Controller\PanierController::class,
                        'action'        => 'addpanier',
                    ],
                ],
            ],

            // affiche le panier de l'utilisateur
            'panier' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/panier',
                    'defaults' => [
                        'controller'    => Controller\PanierController::class,
                        'action'        => 'panier',
                    ],
                ],
            ],

            // supprime un produit du panier
            'removepanier' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/removepanier/:id',
                    'constraints' => [
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller'    => Controller\PanierController::class,
                        'action'        => 'removepanier',
                    ],
                ],
            ],

            // affiche le formulaire de paiement du panier et traite également le retour après la saisie de l'utilisateur
            'payer' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/payer',
                    'defaults' => [
                        'controller'    => Controller\PanierController::class,
                        'action'        => 'payer',
                    ],
                ],
            ],

            // affiche les informations d'un paiement fructueux
            'infopaiement' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/infopaiement',
                    'defaults' => [
                        'controller'    => Controller\PanierController::class,
                        'action'        => 'infopaiement',
                    ],
                ],
            ],

            /*  ---------------- AdminController ---------------- */

            // liste les produits du catalogue pour leur management
            'admin' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/admin[/:page]',
                    'constraints' => [
                        'page' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller'    => Controller\AdminController::class,
                        'action'        => 'admin',
                        'page'        => 1,
                    ],
                ],
            ],

            // supprime un produit
            'delete' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/delete/:id',
                    'constraints' => [
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller'    => Controller\AdminController::class,
                        'action'        => 'delete',
                    ],
                ],
            ],

            // modifit un produit
            'edit' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/edit/:id',
                    'constraints' => [
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller'    => Controller\AdminController::class,
                        'action'        => 'edit',
                    ],
                ],
            ],

            // ajoute un produit
            'add' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/add',
                    'defaults' => [
                        'controller'    => Controller\AdminController::class,
                        'action'        => 'add',
                    ],
                ],
            ],
        ],
    ],
    'access_filter' => [
        'options' => [
            'mode' => 'restrictive'
        ],
        'controllers' => [
            Controller\IndexController::class => [
                ['actions' => ['index', 'produit', 'historique', 'compte', 'addpanier', 'panier', 'removepanier', 'payer', 'infopaiement'], 'allow' => '*'],
                ['actions' => ['edit'], 'allow' => '@']
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            Services\ProductTable::class => Services\Factories\ProductTableFactory::class,
            Services\ProductTableGateway::class => Services\Factories\ProductTableGatewayFactory::class,
            Services\PanierTable::class => Services\Factories\PanierTableFactory::class,
            Services\PanierTableGateway::class => Services\Factories\PanierTableGatewayFactory::class,
            Services\MetadataTable::class => Services\Factories\MetadataTableFactory::class,
            Services\MetadataTableGateway::class => Services\Factories\MetadataTableGatewayFactory::class,
            Services\HistoriqueTable::class => Services\Factories\HistoriqueTableFactory::class,
            Services\HistoriqueTableGateway::class => Services\Factories\HistoriqueTableGatewayFactory::class,
            Services\UserprivilegeTable::class => Services\Factories\UserprivilegeTableFactory::class,
            Services\UserprivilegeTableGateway::class => Services\Factories\UserprivilegeTableGatewayFactory::class,
            Services\PrivilegeTable::class => Services\Factories\PrivilegeTableFactory::class,
            Services\PrivilegeTableGateway::class => Services\Factories\PrivilegeTableGatewayFactory::class,
            Services\NavManager::class => Services\Factories\NavManagerFactory::class,
         ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Controller\Factories\IndexControllerFactory::class,
            Controller\PanierController::class => Controller\Factories\PanierControllerFactory::class,
            Controller\AdminController::class => Controller\Factories\AdminControllerFactory::class,
        ],
    ],
    'view_helpers' => [
        'factories' => [
            View\Helper\Menu::class => View\Helper\Factory\MenuFactory::class,
        ],
        'aliases' => [
            'mainMenu' => View\Helper\Menu::class
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];
