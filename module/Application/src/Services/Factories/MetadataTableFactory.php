<?php
namespace Application\Services\Factories;

use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;
use Zend\Authentication\Storage\Session as SessionStorage;
use Interop\Container\ContainerInterface;
use Application\Services\MetadataTableGateway;
use Application\Services\MetadataTable;


/**
 * The factory responsible for creating of authentication service.
 */
class MetadataTableFactory implements FactoryInterface
{
    /**
     * This method creates the Zend\Authentication\AuthenticationService service 
     * and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, 
                    $requestedName, array $options = null)
    {
        $tableGateway = $container->get(MetadataTableGateway::class);
        $table = new MetadataTable($tableGateway);
        return $table;
    }
}
