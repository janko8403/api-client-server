<?php

namespace AssemblyOrders;

use AssemblyOrders\Controller\IndexController;
use AssemblyOrders\Controller\RankingController;
use AssemblyOrders\Controller\SaveController;
use AssemblyOrders\Form\AddRankingForm;
use AssemblyOrders\Form\AssemblyOrderForm;
use AssemblyOrders\Notification\OrderAcceptedSMS;
use AssemblyOrders\Service\RankingService;
use AssemblyOrders\Service\UserService;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Hr\Form\FormFactory;
use Laminas\Mvc\Controller\LazyControllerAbstractFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;

return [
    'router' => [
        'routes' => [
            'assembly-orders' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/assembly-orders',
                    'defaults' => [
                        'controller' => IndexController::class,
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
                    'rankings' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/rankings/:id[/:action]',
                            'defaults' => [
                                'controller' => RankingController::class,
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
                        ],
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            UserService::class => ReflectionBasedAbstractFactory::class,
            RankingService::class => ReflectionBasedAbstractFactory::class,

            AssemblyOrderForm::class => FormFactory::class,
            AddRankingForm::class => ReflectionBasedAbstractFactory::class,

            OrderAcceptedSMS::class => ReflectionBasedAbstractFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            IndexController::class => LazyControllerAbstractFactory::class,
            SaveController::class => LazyControllerAbstractFactory::class,
            RankingController::class => LazyControllerAbstractFactory::class,
        ],
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AttributeDriver::class,
                'paths' => [
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
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
