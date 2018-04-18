<?php
namespace Application\Controller\Factories;

use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;
use Zend\Authentication\Storage\Session as SessionStorage;
use User\Service\AuthAdapter;
use User\Services\AuthManager;
use Interop\Container\ContainerInterface;
use Application\Controller\IndexController;
use Application\Services\FicheTable;
use Application\Services\MetadataTable;

/**
 * The factory responsible for creating of authentication service.
 */
class IndexControllerFactory implements FactoryInterface
{
    /**
     * This method creates the Zend\Authentication\AuthenticationService service 
     * and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, 
                    $requestedName, array $options = null)
    {
        return new IndexController(
            $container->get(AuthManager::class),
            $container->get(FicheTable::class),
            $container->get(MetadataTable::class)
        );
    }
}
