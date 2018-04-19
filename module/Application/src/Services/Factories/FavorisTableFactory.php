<?php
namespace Application\Services\Factories;

use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;
use Zend\Authentication\Storage\Session as SessionStorage;
use Interop\Container\ContainerInterface;
use Application\Services\FavorisTableGateway;
use Application\Services\FavorisTable;
use Application\Services\FicheTable;


/**
 * The factory responsible for creating of authentication service.
 */
class FavorisTableFactory implements FactoryInterface
{
    /**
     * This method creates the Zend\Authentication\AuthenticationService service 
     * and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, 
                    $requestedName, array $options = null)
    {
        $tableGateway = $container->get(FavorisTableGateway::class);
    $table = new FavorisTable($tableGateway, $container->get(FicheTable::class));
        return $table;
    }
}
