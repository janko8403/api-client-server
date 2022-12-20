<?php

namespace NpsRating;

use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Laminas\Mvc\Controller\LazyControllerAbstractFactory;
use Laminas\Router\Http\Segment;
use NpsRating\Controller\IndexController;
use NpsRating\Service\NpsRatingClearService;
use NpsRating\Service\NpsRatingAddCsvService;

return [
    'router' => [
        'routes' => [
            'nps-ratings' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/nps-ratings',
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
                    'clear' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/clear',
                            'defaults' => [
                                'controller' => IndexController::class,
                                'action' => 'clear',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            NpsRatingClearService::class => ReflectionBasedAbstractFactory::class,
            NpsRatingAddCsvService::class => ReflectionBasedAbstractFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            IndexController::class => LazyControllerAbstractFactory::class,
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
