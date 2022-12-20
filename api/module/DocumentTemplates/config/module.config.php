<?php

namespace DocumentTemplates;

use Doctrine\ORM\Mapping\Driver\YamlDriver;
use DocumentTemplates\Controller\IndexController;
use DocumentTemplates\Controller\IndexControllerFactory;
use DocumentTemplates\Controller\PreviewController;
use DocumentTemplates\Controller\PreviewControllerFactory;
use DocumentTemplates\Controller\SaveController;
use DocumentTemplates\Controller\SaveControllerFactory;
use DocumentTemplates\Document\Broker;
use DocumentTemplates\Document\BrokerFactory;
use DocumentTemplates\Document\DataProviderFactory;
use DocumentTemplates\Form\DocumentTemplateForm;
use DocumentTemplates\Form\DocumentTemplateSearchForm;
use DocumentTemplates\Output\OutputFactory;
use DocumentTemplates\Replacer\Comission;
use DocumentTemplates\Replacer\ComissionFactory;
use DocumentTemplates\Replacer\CommissionSummary;
use DocumentTemplates\Replacer\CommissionSummaryFactory;
use DocumentTemplates\Replacer\UserAgreement;
use DocumentTemplates\Replacer\UserAgreementFactory;
use DocumentTemplates\Replacer\UserData;
use DocumentTemplates\Replacer\UserDataFactory;
use DocumentTemplates\Replacer\WorkCertificate;
use DocumentTemplates\Replacer\WorkCertificateFactory;
use DocumentTemplates\Service\DocumentService;
use DocumentTemplates\Service\DocumentServiceFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Hr\Form\FormFactory;
use Hr\Service\ContainerFactory;

return [
    'router' => [
        'routes' => [
            'document-templates' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/document-templates',
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
                    'preview' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/preview/:id',
                            'defaults' => [
                                'controller' => PreviewController::class,
                            ],
                        ],
                    ],
                    'download' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/download/:id',
                            'defaults' => [
                                'controller' => PreviewController::class,
                                'action' => 'download',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            DocumentService::class => DocumentServiceFactory::class,
            DataProviderFactory::class => ContainerFactory::class,
            OutputFactory::class => ContainerFactory::class,
            Broker::class => BrokerFactory::class,

            // data replacers
            UserData::class => UserDataFactory::class,
            WorkCertificate::class => WorkCertificateFactory::class,
            Comission::class => ComissionFactory::class,
            UserAgreement::class => UserAgreementFactory::class,
            CommissionSummary::class => CommissionSummaryFactory::class,

            // FormElements
            DocumentTemplateForm::class => FormFactory::class,
            DocumentTemplateSearchForm::class => FormFactory::class,
        ],
        'lazy_services' => [
            'class_map' => [
                DocumentService::class => DocumentService::class,
            ],
        ],
        'delegators' => [
            DocumentService::class => [
//                LazyServiceFactory::class,
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            IndexController::class => IndexControllerFactory::class,
            SaveController::class => SaveControllerFactory::class,
            PreviewController::class => PreviewControllerFactory::class,
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