<?php
namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\ConsumerController;
use Application\Service\ConsumerManager;

/**
 * This is the factory for GroupController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class ConsumerControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $consumerManager = $container->get(ConsumerManager::class);

        // Instantiate the controller and inject dependencies
        return new ConsumerController($entityManager, $consumerManager);
    }
}
