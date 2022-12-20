<?php

namespace Customers;

use Customers\Controller\IndexController;
use Customers\Controller\IndexControllerFactory;
use Customers\Controller\PickerController;
use Customers\Controller\PickerControllerFactory;
use Customers\Controller\SaveController;
use Customers\Controller\SaveControllerFactory;
use Customers\Controller\TemplateController;
use Customers\Controller\TemplateControllerFactory;
use Customers\Controller\UsersController;
use Customers\Controller\UsersControllerFactory;
use Customers\Form\CustomerEditForm;
use Customers\Form\CustomerForm;
use Customers\Form\CustomerPickerSearchForm;
use Customers\Form\CustomerTemplateForm;
use Customers\Service\CustomerService;
use Customers\Service\CustomerServiceFactory;
use Customers\Service\DetailsService;
use Customers\Service\DetailsServiceFactory;
use Customers\Service\TemplateService;
use Customers\Service\TemplateServiceFactory;
use Customers\Service\UserRelationService;
use Customers\Table\CustomerTable;
use Customers\Table\CustomerTableFactory;
use Doctrine\ORM\Mapping\Driver\YamlDriver;
use Hr\Form\FormFactory;
use Hr\Service\ObjectManagerFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'customers' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/customers',
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
                    'picker' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/picker[/:action]',
                            'defaults' => [
                                'controller' => PickerController::class,
                            ],
                        ],
                    ],
                    'template' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/template',
                            'defaults' => [
                                'controller' => TemplateController::class,
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
                    'users' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/users[/:id[/:action]]',
                            'defaults' => [
                                'controller' => UsersController::class,
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
            PickerController::class => PickerControllerFactory::class,
            TemplateController::class => TemplateControllerFactory::class,
            IndexController::class => IndexControllerFactory::class,
            SaveController::class => SaveControllerFactory::class,
            UsersController::class => UsersControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            CustomerTable::class => CustomerTableFactory::class,
            TemplateService::class => TemplateServiceFactory::class,
            CustomerService::class => CustomerServiceFactory::class,
            DetailsService::class => DetailsServiceFactory::class,
            UserRelationService::class => ObjectManagerFactory::class,

            // FormElements
            CustomerPickerSearchForm::class => FormFactory::class,
            CustomerTemplateForm::class => FormFactory::class,
            CustomerForm::class => FormFactory::class,
            CustomerEditForm::class => FormFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'template_map' => [
            'customer-data' => __DIR__ . '/../view/partial/customer-data.phtml',
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