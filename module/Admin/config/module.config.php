<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'admin' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/admin',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/admin',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
			//'Application\Controller\Application' => 'Application\Controller\IndexController'
			'Admin\Controller\Index' => 'Admin\Controller\IndexController',
			'Admin\Controller\Dashboard' => 'Admin\Controller\DashboardController',
			'Admin\Controller\fairmanage' => 'Admin\Controller\FairmanageController',
			'Admin\Controller\complaint' => 'Admin\Controller\ComplaintController',
			'Admin\Controller\vehicle' => 'Admin\Controller\VehicleController',
			'Admin\Controller\Driver' => 'Admin\Controller\DriverController',
			'Admin\Controller\Device' => 'Admin\Controller\DeviceController',
			'Admin\Controller\Group' => 'Admin\Controller\GroupController',
			'Admin\Controller\Client' => 'Admin\Controller\ClientController',
			'Admin\Controller\Tracking' => 'Admin\Controller\TrackingController',
			'Admin\Controller\Promotion' => 'Admin\Controller\PromotionController',
			'Admin\Controller\Role' => 'Admin\Controller\RoleController',
			'Admin\Controller\User' => 'Admin\Controller\UserController',
			'Admin\Controller\Package' => 'Admin\Controller\PackageController',
			'Admin\Controller\Payment' => 'Admin\Controller\PaymentController',
    		'Admin\Controller\Callcentre' => 'Admin\Controller\CallcentreController',
        ),
    ),
    'view_manager' => array(
		'strategies' => array(
			'ViewJsonStrategy',
		),
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
           // 'layout/layout'           => __DIR__ . '/../view/layout/dashboard.phtml',
            //'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
	////////////
	'user' => array(
		'type' => 'Segment',
		'options' => array(
			'route' => '/api/user/[/:id]',
			'default' => array(
			'controller' => 'Admin\Controller\user',
			),
		),
	),
	
	
);
