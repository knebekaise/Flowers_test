<?php

namespace Finput;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\ConfigProviderInterface;


class Module implements ConfigProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $ViewHelperManager = $e->getApplication()
            ->getServiceManager()
            ->get('ViewHelperManager');
        $e->getApplication()
            ->getServiceManager()
            ->get('ViewHelperManager')
            ->setFactory('finput', function($sm) use ($ViewHelperManager) {
                $viewHelper = new \Finput\Form\View\Helper\Finput();
                $viewHelper->setInlineScript($ViewHelperManager->get('inlinescript'));
                $viewHelper->setHeadLink($ViewHelperManager->get('HeadLink'));
                $viewHelper->setUrl($ViewHelperManager->get('url'));
                return $viewHelper;
            });
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getControllerPluginConfig()
    {

    }

    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array(
                //'formelement' => 'Finput\Form\View\Helper\FormElement',
                //'finput'     => 'Finput\Form\View\Helper\Finput',
            ),
        );
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__=> __DIR__ . '/src/',
                ),
            ),
        );
    }
}
