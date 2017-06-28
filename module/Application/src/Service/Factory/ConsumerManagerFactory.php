<?php
namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\ConsumerManager;
use Application\Service\ImageManager;

/**
 * This is the factory for ConsumerManager. Its purpose is to instantiate the
 * service.
 */
class ConsumerManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $imageManager = $container->get(ImageManager::class);

        // Instantiate the service and inject dependencies
        return new ConsumerManager($entityManager, $imageManager);
    }
}
