<?php
namespace Application\Controller\Factories;

use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;
use Zend\Authentication\Storage\Session as SessionStorage;
use User\Service\AuthAdapter;
use User\Services\AuthManager;
use Interop\Container\ContainerInterface;
use Application\Controller\AdminController;
use Application\Services\FicheTable;
use Application\Services\AttributTable;
use Application\Services\MetadataTable;
use Application\Services\FavorisTable;

/**
 * The factory responsible for creating of authentication service.
 */
class AdminControllerFactory implements FactoryInterface
{
    /**
     * This method creates the Zend\Authentication\AuthenticationService service 
     * and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, 
                    $requestedName, array $options = null)
    {
        return new AdminController(
            $container->get(AuthManager::class),
            $container->get(FicheTable::class),
            $container->get(MetadataTable::class),
            $container->get(AttributTable::class),
            $container->get(FavorisTable::class)
        );
    }
}
