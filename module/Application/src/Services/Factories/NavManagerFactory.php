<?php
namespace Application\Services\Factories;

use Interop\Container\ContainerInterface;
use Application\Services\NavManager;
use Application\Services\UserprivilegeTable;

/**
 * This is the factory class for NavManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class NavManagerFactory
{
    /**
     * This method creates the NavManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $authService = $container->get(\Zend\Authentication\AuthenticationService::class);
        
        $viewHelperManager = $container->get('ViewHelperManager');
        $urlHelper = $viewHelperManager->get('url');
        
        return new NavManager($authService, $urlHelper, $container->get(UserprivilegeTable::class));
    }
}
