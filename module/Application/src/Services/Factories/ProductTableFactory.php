<?php
namespace Application\Services\Factories;

use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;
use Zend\Authentication\Storage\Session as SessionStorage;
use Interop\Container\ContainerInterface;
use Application\Services\ProductTableGateway;
use Application\Services\ProductTable;
use Application\Services\MetadataTable;


/**
 * The factory responsible for creating of authentication service.
 */
class ProductTableFactory implements FactoryInterface
{
    /**
     * This method creates the Zend\Authentication\AuthenticationService service 
     * and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, 
                    $requestedName, array $options = null)
    {
        $tableGateway = $container->get(ProductTableGateway::class);
    $table = new ProductTable($tableGateway, $container->get(MetadataTable::class));
        return $table;
    }
}
