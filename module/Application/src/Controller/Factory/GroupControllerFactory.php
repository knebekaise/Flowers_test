<?php
namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\GroupController;
use Application\Service\GroupManager;

/**
 * This is the factory for GroupController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class GroupControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $groupManager = $container->get(GroupManager::class);
        
        // Instantiate the controller and inject dependencies
        return new GroupController($entityManager, $groupManager);
    }
}
