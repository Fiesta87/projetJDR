<?php
namespace Application\Controller\Factories;

use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;
use Zend\Authentication\Storage\Session as SessionStorage;
use User\Service\AuthAdapter;
use User\Services\AuthManager;
use Interop\Container\ContainerInterface;
use Application\Controller\PanierController;
use Application\Services\ProductTable;
use Application\Services\PanierTable;
use Application\Services\MetadataTable;
use Application\Services\HistoriqueTable;

/**
 * The factory responsible for creating of authentication service.
 */
class PanierControllerFactory implements FactoryInterface
{
    /**
     * This method creates the Zend\Authentication\AuthenticationService service 
     * and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, 
                    $requestedName, array $options = null)
    {
        return new PanierController(
            $container->get(AuthManager::class),
            $container->get(ProductTable::class),
            $container->get(PanierTable::class),
            $container->get(MetadataTable::class),
            $container->get(HistoriqueTable::class)
        );
    }
}
