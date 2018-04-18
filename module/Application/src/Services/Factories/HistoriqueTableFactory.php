<?php
namespace Application\Services\Factories;

use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;
use Zend\Authentication\Storage\Session as SessionStorage;
use Interop\Container\ContainerInterface;
use Application\Services\HistoriqueTableGateway;
use Application\Services\HistoriqueTable;
use Application\Services\ProductTable;


/**
 * The factory responsible for creating of authentication service.
 */
class HistoriqueTableFactory implements FactoryInterface
{
    /**
     * This method creates the Zend\Authentication\AuthenticationService service 
     * and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, 
                    $requestedName, array $options = null)
    {
        $tableGateway = $container->get(HistoriqueTableGateway::class);
        $table = new HistoriqueTable($tableGateway, $container->get(ProductTable::class));
        return $table;
    }
}
