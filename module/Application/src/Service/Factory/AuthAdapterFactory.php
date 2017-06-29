<?php

namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Application\Service\AuthAdapter;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * This is the factory class for AuthAdapter service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class AuthAdapterFactory implements FactoryInterface
{
    /**
     * This method creates the AuthAdapter service and returns its instance.
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        $config = $container->get('config');
        if (!(isset($config['auth_data']['login']) && isset($config['auth_data']['password']))) {
            throw new \Exception('Configuration param "auth_data" info is empty');
        }

        // Create the AuthAdapter and inject dependency to its constructor.
        return new AuthAdapter($config['auth_data']['login'], $config['auth_data']['password']);
    }
}
