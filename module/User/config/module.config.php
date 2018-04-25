<?php
namespace User;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;



return [
    'router' => [
        'routes' => [

            // affichage et traitement de la réponse du formulaire pour connecter un utilisateur
            'login' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/login',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'login',
                    ],
                ],
            ],

            // deconnecte un utilisateur et redirige sur la page de login
            'logout' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/logout',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'logout',
                    ],
                ],
            ],

            // affiche le compte de l'utilisateur pour modifier des paramètres personnels
            'compte' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/compte',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'compte',
                    ],
                ],
            ],

            // affiche le compte de l'utilisateur pour modifier des paramètres personnels
            'creation' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/creation',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'creation',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\AuthController::class => Controller\Factories\AuthControllerFactory::class,
        ],
    ],
    
    'service_manager' => [
        'factories' => [
            Services\UserManager::class => Services\Factories\UserManagerFactory::class,
            Services\UserGateway::class => Services\Factories\UserGatewayFactory::class,
            Services\AuthManager::class => Services\Factories\AuthManagerFactory::class,
            Services\AuthAdapter::class => Services\Factories\AuthAdapterFactory::class,
            \Zend\Authentication\AuthenticationService::class => Services\Factories\AuthenticationServiceFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];