<?php
namespace User\Controller\Factories;

use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;
use Zend\Authentication\Storage\Session as SessionStorage;
use Interop\Container\ContainerInterface;
use User\Services\UserManager;
use User\Services\AuthManager;
use User\Controller\AuthController;

class AuthControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, 
                    $requestedName, array $options = null)
    {
        $userManager = $container->get(UserManager::class);
        $authManager = $container->get(AuthManager::class);
        return new AuthController($userManager, $authManager);
    }
}
