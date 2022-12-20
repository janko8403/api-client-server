<?php

namespace Users;

use Doctrine\ORM\Mapping\Driver\YamlDriver;
use Hr\Form\FormFactory;
use Hr\Service\ObjectManagerFactory;
use Laminas\Mvc\Controller\LazyControllerAbstractFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Notifications\Notification\NotificationFactory;
use Users\Controller\ChangePasswordController;
use Users\Controller\ChangePasswordControllerFactory;
use Users\Controller\DataController;
use Users\Controller\DataControllerFactory;
use Users\Controller\DetailsController;
use Users\Controller\IndexController;
use Users\Controller\IndexControllerFactory;
use Users\Controller\LoginController;
use Users\Controller\LoginControllerFactory;
use Users\Controller\PickerController;
use Users\Controller\PickerControllerFactory;
use Users\Controller\SaveController;
use Users\Controller\SaveControllerFactory;
use Users\Form\ChangePasswordForm;
use Users\Form\UserForm;
use Users\Form\UserSearchForm;
use Users\Notification\PasswordResetNotification;
use Users\Restriction\RestrictionFactory;
use Users\Restriction\RestrictionFactoryFactory;
use Users\Restriction\RestrictionService;
use Users\Restriction\RestrictionServiceFactory;
use Users\Service\DetailsService;
use Users\Service\DetailsServiceFactory;
use Users\Service\EventService;
use Users\Service\LoginService;
use Users\Service\LoginServiceFactory;
use Users\Service\RegistrationService;
use Users\Service\RegistrationServiceFactory;
use Users\Service\StatusService;
use Users\Service\UserService;
use Users\Service\UserServiceFactory;
use Users\Table\UserTable;
use Users\Table\UserTableFactory;

return [
    'router' => [
        'routes' => [
            'users' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/users',
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
                    'status' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/status/:id',
                            'defaults' => [
                                'controller' => SaveController::class,
                                'action' => 'status',
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
                    'change-password' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/change-password',
                            'defaults' => [
                                'controller' => ChangePasswordController::class,
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'user' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/:id',
                                    'defaults' => [
                                        'action' => 'user',
                                    ],
                                ],
                            ],
                            'current' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/current',
                                    'defaults' => [
                                        'action' => 'current',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'data' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/data/:id',
                            'defaults' => [
                                'controller' => DataController::class,
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'download' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/download/:field',
                                    'defaults' => [
                                        'controller' => DataController::class,
                                        'action' => 'download',
                                    ],
                                ],
                            ],
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
                    'login' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/login[/:action[/:id]]',
                            'defaults' => [
                                'controller' => LoginController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'details' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/details/:id',
                            'defaults' => [
                                'controller' => DetailsController::class,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            IndexController::class => IndexControllerFactory::class,
            SaveController::class => SaveControllerFactory::class,
            DataController::class => DataControllerFactory::class,
            ChangePasswordController::class => ChangePasswordControllerFactory::class,
            PickerController::class => PickerControllerFactory::class,
            LoginController::class => LoginControllerFactory::class,
            DetailsController::class => LazyControllerAbstractFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            UserService::class => UserServiceFactory::class,
            RegistrationService::class => RegistrationServiceFactory::class,
            RestrictionFactory::class => RestrictionFactoryFactory::class,
            RestrictionService::class => RestrictionServiceFactory::class,
            UserTable::class => UserTableFactory::class,
            LoginService::class => LoginServiceFactory::class,
            DetailsService::class => DetailsServiceFactory::class,
            StatusService::class => ObjectManagerFactory::class,
            EventService::class => ObjectManagerFactory::class,

            // form elements
            UserSearchForm::class => FormFactory::class,
            UserForm::class => FormFactory::class,
            ChangePasswordForm::class => FormFactory::class,

            // notifications
            PasswordResetNotification::class => NotificationFactory::class,
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
//                'cache' => 'array',
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