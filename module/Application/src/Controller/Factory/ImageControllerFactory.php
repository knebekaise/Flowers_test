<?php
namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\ImageController;
use Application\Service\ImageManager;

/**
 * This is the factory for GroupController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class ImageControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $imageManager = $container->get(ImageManager::class);

        // Instantiate the controller and inject dependencies
        return new ImageController($imageManager);
    }
}
