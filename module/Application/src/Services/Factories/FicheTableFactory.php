<?php
namespace Application\Services\Factories;

use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;
use Zend\Authentication\Storage\Session as SessionStorage;
use Interop\Container\ContainerInterface;
use Application\Services\FicheTableGateway;
use Application\Services\FicheTable;
use Application\Services\MetadataTable;
use Application\Services\AttributTable;
use User\Services\UserManager;


/**
 * The factory responsible for creating of authentication service.
 */
class FicheTableFactory implements FactoryInterface
{
    /**
     * This method creates the Zend\Authentication\AuthenticationService service 
     * and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, 
                    $requestedName, array $options = null)
    {
        $tableGateway = $container->get(FicheTableGateway::class);
    $table = new FicheTable($tableGateway, $container->get(MetadataTable::class), $container->get(AttributTable::class), $container->get(UserManager::class));
        return $table;
    }
}
