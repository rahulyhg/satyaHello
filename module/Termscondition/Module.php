<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Termscondition;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
			{
			// You may not need to do this if you’re doing it elsewhere in your
			// application
			$eventManager = $e->getApplication()->getEventManager();
			$moduleRouteListener = new ModuleRouteListener();
			$moduleRouteListener->attach($eventManager);

			$sharedEventManager = $eventManager->getSharedManager(); // The shared event manager
			$sharedEventManager->attach(__NAMESPACE__, MvcEvent::EVENT_DISPATCH, function ($e) {
			$controller = $e->getTarget(); // The controller which is dispatched
			$controllerName = $controller->getEvent()
			->getRouteMatch()->getParam('controller');
			if (!in_array($controllerName,
			array('administration'))
			) {
			$controller->layout('layout/dashboard');
			}
			});
			}

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
	

}
