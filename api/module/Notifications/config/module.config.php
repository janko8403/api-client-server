<?php

namespace Notifications;

use Doctrine\ORM\Mapping\Driver\YamlDriver;
use Hr\Form\FormFactory;
use Hr\Service\ContainerFactory;
use Hr\Service\ObjectManagerFactory;
use Laminas\Mvc\Controller\LazyControllerAbstractFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;
use Notifications\Controller\CycleController;
use Notifications\Controller\IndexController;
use Notifications\Controller\IndexControllerFactory;
use Notifications\Controller\SaveController;
use Notifications\Controller\SaveControllerFactory;
use Notifications\Controller\SmsCallbackController;
use Notifications\Controller\SmsCallbackControllerFactory;
use Notifications\Controller\SmsHistoryController;
use Notifications\Controller\SmsHistoryControllerFactory;
use Notifications\Form\CycleForm;
use Notifications\Form\NotificationForm;
use Notifications\Form\SmsHistorySearchForm;
use Notifications\Mail\MailService;
use Notifications\Mail\MailServiceFactory;
use Notifications\RecipientStrategy\RecipientFactory;
use Notifications\RecipientStrategy\RecipientFactoryFactory;
use Notifications\Service\CycleService;
use Notifications\Sms\CallbackService;
use Notifications\Sms\CallbackServiceFactory;
use Notifications\Sms\HistoryService;
use Notifications\Sms\SmsService;
use Notifications\Sms\SmsServiceFactory;
use Notifications\SmsCallback\CallbackFactory;
use Notifications\Transport\TransportFactory;
use Notifications\Transport\TransportFactoryFactory;

return [
    'router' => [
        'routes' => [
            'sms-callback' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/sms-callback/:action',
                    'defaults' => [
                        'controller' => SmsCallbackController::class,
                    ],
                ],
            ],
            'sms-history' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/sms-history[/:action]',
                    'defaults' => [
                        'controller' => SmsHistoryController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'notifications' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/notifications',
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
                    'cycles' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/cycles[/:action[/:id]]',
                            'defaults' => [
                                'controller' => CycleController::class,
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
            SmsCallbackController::class => SmsCallbackControllerFactory::class,
            SmsHistoryController::class => SmsHistoryControllerFactory::class,
            IndexController::class => IndexControllerFactory::class,
            SaveController::class => SaveControllerFactory::class,
            CycleController::class => LazyControllerAbstractFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            MailService::class => MailServiceFactory::class,
            RecipientFactory::class => RecipientFactoryFactory::class,
            TransportFactory::class => TransportFactoryFactory::class,
            SmsService::class => SmsServiceFactory::class,
            CallbackService::class => CallbackServiceFactory::class,
            CallbackFactory::class => ContainerFactory::class,
            HistoryService::class => ObjectManagerFactory::class,
            CycleService::class => ReflectionBasedAbstractFactory::class,

            SmsHistorySearchForm::class => FormFactory::class,
            NotificationForm::class => FormFactory::class,
            CycleForm::class => FormFactory::class,
        ],
//        'lazy_services' => [
//            'class_map' => [
//                MailService::class => MailService::class,
//            ],
//        ],
//        'delegators' => [
//            MailService::class => [LazyServiceFactory::class],
//        ],
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => YamlDriver::class,
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
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
