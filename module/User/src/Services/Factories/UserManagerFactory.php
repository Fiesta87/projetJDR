<?php
namespace User\Services\Factories;

use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;
use Zend\Authentication\Storage\Session as SessionStorage;
use Interop\Container\ContainerInterface;
use User\Services\UserGateway;
use User\Services\UserManager;

class UserManagerFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, 
                    $requestedName, array $options = null)
    {
        $tableGateway = $container->get(UserGateway::class);
        $table = new UserManager($tableGateway);
        return $table;
    }
}
