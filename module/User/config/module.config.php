<?php
namespace User;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;



return [
    'router' => [
        'routes' => [
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