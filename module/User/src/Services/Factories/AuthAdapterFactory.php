<?php
namespace User\Services\Factories;

use Interop\Container\ContainerInterface;
use User\Services\AuthAdapter;
use Zend\ServiceManager\Factory\FactoryInterface;
use User\Services\UserManager;

class AuthAdapterFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $table = $container->get(UserManager::class);
        return new AuthAdapter($table);
    }
}
