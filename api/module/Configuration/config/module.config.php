<?php

namespace Configuration;

use Configuration\Controller\HolidayDateController;
use Configuration\Controller\IndexController;
use Configuration\Controller\IndexControllerFactory;
use Configuration\Controller\ObjectController;
use Configuration\Controller\ObjectControllerFactory;
use Configuration\Controller\ResourceController;
use Configuration\Controller\ResourceControllerFactory;
use Configuration\Controller\SaveController;
use Configuration\Controller\SaveControllerFactory;
use Configuration\Form\MenuSearchFrom;
use Configuration\Form\ResourceForm;
use Configuration\Form\ResourceFormFactory;
use Configuration\HolidayDate\HolidayDateService;
use Configuration\Object\ObjectService;
use Configuration\Object\ObjectServiceFactory;
use Configuration\ObjectField\ObjectFieldService;
use Configuration\ObjectField\ObjectFieldServiceFactory;
use Configuration\ObjectFieldDetail\ObjectFieldDetailService;
use Configuration\ObjectFieldDetail\ObjectFieldDetailServiceFactory;
use Configuration\Resource\ResourceService;
use Configuration\Resource\ResourceServiceFactory;
use Doctrine\ORM\Mapping\Driver\YamlDriver;
use Hr\Form\FormFactory;
use Laminas\Mvc\Controller\LazyControllerAbstractFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;

return [
    'router' => [
        'routes' => [
            'configuration' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/configuration',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'add' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/add',
                            'defaults' => [
                                'controller' => SaveController::class,
                                'action' => 'add',
                            ],
                        ],
                    ],
                    'edit' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/edit/:id',
                            'defaults' => [
                                'controller' => SaveController::class,
                                'action' => 'edit',
                            ],
                        ],
                    ],
                    'subresourceEdit' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/subresourceEdit/:id',
                            'defaults' => [
                                'controller' => SaveController::class,
                                'action' => 'subresourceEdit',
                            ],
                        ],
                    ],
                    'resourcePositions' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/resourcePositions/:id',
                            'defaults' => [
                                'controller' => ResourceController::class,
                                'action' => 'resourcePositions',
                            ],
                        ],
                    ],
                    'saveResourcePositions' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/saveResourcePositions/:id',
                            'defaults' => [
                                'controller' => ResourceController::class,
                                'action' => 'saveResourcePositions',
                            ],
                        ],
                    ],
                    'submenu' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/submenu/:id',
                            'defaults' => [
                                'controller' => ResourceController::class,
                                'action' => 'submenu',
                            ],
                        ],
                    ],
                    'editVisibility' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/editVisibility/:id',
                            'defaults' => [
                                'controller' => ObjectController::class,
                                'action' => 'editVisibility',
                            ],
                        ],
                    ],
                    'saveVisibility' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/saveVisibility/:id',
                            'defaults' => [
                                'controller' => ObjectController::class,
                                'action' => 'saveVisibility',
                            ],
                        ],
                    ],
                    'editSequence' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/editSequence/:id',
                            'defaults' => [
                                'controller' => ObjectController::class,
                                'action' => 'editSequence',
                            ],
                        ],
                    ],
                    'saveSequence' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/saveSequence',
                            'defaults' => [
                                'controller' => ObjectController::class,
                                'action' => 'saveSequence',
                            ],
                        ],
                    ],
                    'holidayDates' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/holiday-dates',
                            'defaults' => [
                                'controller' => HolidayDateController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'default' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/:action[/:id]',
                                ],
                            ],
                            'add' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/add',
                                    'defaults' => [
                                        'controller' => HolidayDateController::class,
                                        'action' => 'add',
                                    ],
                                ],
                            ],
                            'remove' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/remove/:id',
                                    'defaults' => [
                                        'controller' => HolidayDateController::class,
                                        'action' => 'remove',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            HolidayDateService::class => ReflectionBasedAbstractFactory::class,
            ObjectService::class => ObjectServiceFactory::class,
            ResourceService::class => ResourceServiceFactory::class,
            ObjectFieldService::class => ObjectFieldServiceFactory::class,
            ObjectFieldDetailService::class => ObjectFieldDetailServiceFactory::class,

            // FormElements
            MenuSearchFrom::class => FormFactory::class,
            ResourceForm::class => ResourceFormFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            HolidayDateController::class => LazyControllerAbstractFactory::class,
            IndexController::class => IndexControllerFactory::class,
            ResourceController::class => ResourceControllerFactory::class,
            SaveController::class => SaveControllerFactory::class,
            ObjectController::class => ObjectControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => YamlDriver::class,
//                'cache' => 'redis',
                'paths' => [
                    __DIR__ . '/../src/Yaml',
                    __DIR__ . '/../src/Entity',
                    __DIR__ . '/../src/Repository',
                ],
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ => __NAMESPACE__ . '_driver',
                ],
            ],
        ],
    ],
];