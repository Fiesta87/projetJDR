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

            // affichage d'une fiche spécifique
            'fiche' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/fiche/:id',
                    'constraints' => [
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'fiche',
                    ],
                ],
            ],

            // téléchargement d'une fiche spécifique
            'telecharge' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/telecharge/:id',
                    'constraints' => [
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'telecharge',
                    ],
                ],
            ],

            // favoris
            'favoris' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/favoris',
                    'defaults' => [
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'favoris',
                    ],
                ],
            ],

            // ajout aux favoris
            'addfavoris' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/addfavoris/:id',
                    'constraints' => [
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'addfavoris',
                    ],
                ],
            ],

            // suppression des favoris
            'removefavoris' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/removefavoris/:id',
                    'constraints' => [
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'removefavoris',
                    ],
                ],
            ],

            /*  ---------------- AdminController ---------------- */

            // liste les produits du catalogue pour leur management
            'admin' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/admin',
                    'defaults' => [
                        'controller'    => Controller\AdminController::class,
                        'action'        => 'admin',
                    ],
                ],
            ],

            // supprime une fiche
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

            // supprime un attribut
            'deleteattribut' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/editfiche/:idfiche/deleteattribut/:idattribut',
                    'constraints' => [
                        'idfiche' => '[0-9]+',
                        'idattribut' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller'    => Controller\AdminController::class,
                        'action'        => 'deleteattribut',
                    ],
                ],
            ],

            // ajoute un attribut à une fiche
            'addattribut' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/editfiche/:idfiche/addattribut[/:idattributparent]',
                    'constraints' => [
                        'idfiche' => '[0-9]+',
                        'idattributparent' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller'    => Controller\AdminController::class,
                        'action'        => 'addattribut',
                    ],
                ],
            ],

            // modifit une fiche
            'editfiche' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/editfiche/:id',
                    'constraints' => [
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller'    => Controller\AdminController::class,
                        'action'        => 'editfiche',
                    ],
                ],
            ],

            // ajoute une fiche
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
    ],/*
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
    ],*/
    'service_manager' => [
        'factories' => [
            Services\FicheTable::class => Services\Factories\FicheTableFactory::class,
            Services\FicheTableGateway::class => Services\Factories\FicheTableGatewayFactory::class,
            Services\AttributTable::class => Services\Factories\AttributTableFactory::class,
            Services\AttributTableGateway::class => Services\Factories\AttributTableGatewayFactory::class,
            Services\MetadataTable::class => Services\Factories\MetadataTableFactory::class,
            Services\MetadataTableGateway::class => Services\Factories\MetadataTableGatewayFactory::class,
            Services\FavorisTable::class => Services\Factories\FavorisTableFactory::class,
            Services\FavorisTableGateway::class => Services\Factories\FavorisTableGatewayFactory::class,
            Services\NavManager::class => Services\Factories\NavManagerFactory::class,
         ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Controller\Factories\IndexControllerFactory::class,
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
