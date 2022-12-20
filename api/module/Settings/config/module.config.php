<?php

namespace Settings;

use Configuration\Form\MonitoringCategoryForm;
use Doctrine\ORM\Mapping\Driver\YamlDriver;
use Laminas\Mvc\Controller\LazyControllerAbstractFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;
use Settings\Controller\AddressBarController;
use Settings\Controller\DictionariesController;
use Settings\Controller\DictionariesExtendController;
use Settings\Controller\DictionariesOldController;
use Settings\Controller\MonitoringCategoriesController;
use Settings\Controller\NotificationHoursController;
use Settings\Controller\PositionVisibilityController;
use Settings\Controller\RegionsController;
use Settings\Controller\SubchainController;
use Settings\Controller\SubchainControllerFactory;
use Settings\Form\AddressBarForm;
use Settings\Form\CopyPrivilegesForm;
use Settings\Form\DictionaryDetailForm;
use Settings\Form\RegionAssignForm;
use Settings\Form\SubchainForm;
use Settings\Form\SubchainSearchForm;
use Settings\Form\SubregionAssignForm;
use Settings\Service\CacheService;
use Settings\Service\CacheServiceFactory;
use Settings\Service\DictionaryDetailsDescriptionService;
use Settings\Service\DictionaryService;
use Settings\Service\FieldDisablerService;
use Settings\Service\FieldDisablerServiceFactory;
use Settings\Service\PositionVisibilityService;
use Settings\Service\PositionVisibilityServiceFactory;
use Settings\Service\RegionService;
use Settings\View\Helper\AddressBarHelper;
use Settings\View\Helper\AddressBarHelperFactory;

return [
    'router' => [
        'routes' => [
            'settings' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/settings',
                    'defaults' => [
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'dictionaries' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/dictionaries',
                            'defaults' => [
                                'controller' => DictionariesController::class,
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'details' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/details/:id',
                                    'defaults' => [
                                        'action' => 'details',
                                    ],
                                ],
                            ],
                            'add' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/add/:id',
                                    'defaults' => [
                                        'action' => 'add',
                                    ],
                                ],
                            ],
                            'edit' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/add/d:dictionary/:id',
                                    'defaults' => [
                                        'action' => 'edit',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'dictionaries-old' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/dictionaries-old',
                            'defaults' => [
                                'controller' => DictionariesOldController::class,
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'details' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/details/:id',
                                    'defaults' => [
                                        'action' => 'details',
                                    ],
                                ],
                            ],
                            'add' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/add/:id[/p:parent]',
                                    'defaults' => [
                                        'action' => 'add',
                                    ],
                                ],
                            ],
                            'edit' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/edit/:id',
                                    'defaults' => [
                                        'action' => 'edit',
                                    ],
                                ],
                            ],
                            'connected' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/connected/:id',
                                    'defaults' => [
                                        'action' => 'connected',
                                    ],
                                ],
                            ],
                            'add-connected' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/add-connected/:id',
                                    'defaults' => [
                                        'action' => 'add-connected',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'dictionaries-extend' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/dictionaries-extend',
                            'defaults' => [
                                'controller' => DictionariesExtendController::class,
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'add' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/add/:id',
                                    'defaults' => [
                                        'action' => 'add',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'position-visibility' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/position-visibility',
                            'defaults' => [
                                'controller' => PositionVisibilityController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'save' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/save',
                                    'defaults' => [
                                        'action' => 'save',
                                    ],
                                ],
                            ],
                            'copy' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/copy',
                                    'defaults' => [
                                        'action' => 'copy',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'address-bar' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/address-bar',
                            'defaults' => [
                                'controller' => AddressBarController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'save' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/save',
                                    'defaults' => [
                                        'action' => 'save',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'subchains' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/subchains',
                            'defaults' => [
                                'controller' => SubchainController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'add' => [
                                'type' => Literal::class,
                                'options' => [
                                    'route' => '/add',
                                    'defaults' => [
                                        'action' => 'add',
                                    ],
                                ],
                            ],
                            'edit' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/edit/:id',
                                    'defaults' => [
                                        'action' => 'edit',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'regions' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/regions[/:action]',
                            'defaults' => [
                                'controller' => RegionsController::class,
                                'action' => 'index',
                            ],
                        ],
//                        'may_terminate' => true,
//                        'child_routes' => [
//                            'add' => [
//                                'type' => Literal::class,
//                                'options' => [
//                                    'route' => '/add',
//                                    'defaults' => [
//                                        'action' => 'add'
//                                    ]
//                                ]
//                            ],
//                            'edit' => [
//                                'type' => Segment::class,
//                                'options' => [
//                                    'route' => '/edit/:id',
//                                    'defaults' => [
//                                        'action' => 'edit'
//                                    ]
//                                ]
//                            ],
//                        ]
                    ],
                    'notification-hours' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/notification-hours[/:action]',
                            'defaults' => [
                                'controller' => NotificationHoursController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            DictionariesController::class => LazyControllerAbstractFactory::class,
            DictionariesOldController::class => LazyControllerAbstractFactory::class,
            PositionVisibilityController::class => LazyControllerAbstractFactory::class,
            AddressBarController::class => LazyControllerAbstractFactory::class,
            MonitoringCategoriesController::class => LazyControllerAbstractFactory::class,
            SubchainController::class => SubchainControllerFactory::class,
            RegionsController::class => LazyControllerAbstractFactory::class,
            NotificationHoursController::class => LazyControllerAbstractFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            DictionaryService::class => ReflectionBasedAbstractFactory::class,
            CacheService::class => CacheServiceFactory::class,
            PositionVisibilityService::class => PositionVisibilityServiceFactory::class,
            DictionaryDetailsDescriptionService::class => ReflectionBasedAbstractFactory::class,
            RegionService::class => ReflectionBasedAbstractFactory::class,
            FieldDisablerService::class => FieldDisablerServiceFactory::class,

            // FormElements
            DictionaryDetailForm::class => ReflectionBasedAbstractFactory::class,
            AddressBarForm::class => ReflectionBasedAbstractFactory::class,
            MonitoringCategoryForm::class => ReflectionBasedAbstractFactory::class,
            CopyPrivilegesForm::class => ReflectionBasedAbstractFactory::class,
            SubchainForm::class => ReflectionBasedAbstractFactory::class,
            SubchainSearchForm::class => ReflectionBasedAbstractFactory::class,
            SubregionAssignForm::class => ReflectionBasedAbstractFactory::class,
            RegionAssignForm::class => ReflectionBasedAbstractFactory::class,
        ],
    ],
    'view_helpers' => [
        'factories' => [
            AddressBarHelper::class => AddressBarHelperFactory::class,
        ],
        'aliases' => [
            'addressBar' => AddressBarHelper::class,
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
//                    __DIR__ . '/../src/Repository',
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