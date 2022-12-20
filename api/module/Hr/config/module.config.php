<?php

namespace Hr;

use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Driver\YamlDriver;
use Doctrine\Persistence\ObjectManager;
use Hr\Acl\AclService;
use Hr\Acl\AclServiceFactory;
use Hr\Authentication\AuthenticationService;
use Hr\Authentication\AuthenticationServiceFactory;
use Hr\Authorization\AuthorizationService;
use Hr\Authorization\AuthorizationServiceFactory;
use Hr\Cache\CacheStorageFactory;
use Hr\Cache\RedisFactory;
use Hr\Controller\ActivityLogController;
use Hr\Controller\ActivityLogControllerFactory;
use Hr\Controller\AutocompleteController;
use Hr\Controller\AutocompleteControllerFactory;
use Hr\Controller\CronController;
use Hr\Db\Factory\InstanceAdapterFactory;
use Hr\Dictionary\DictionaryService;
use Hr\Dictionary\DictionaryServiceFactory;
use Hr\Doctrine\Cache\FilesystemFactory;
use Hr\File\FileService;
use Hr\File\FileServiceFactory;
use Hr\Form\Element\Dropdown;
use Hr\Form\Element\ImageElement;
use Hr\Form\Element\RecordPicker;
use Hr\Form\FormService;
use Hr\Form\FormServiceFactory;
use Hr\I18n\Translator\Loader\HrDatabaseLoader;
use Hr\I18n\Translator\Loader\HrDatabaseLoaderFactory;
use Hr\InputFilter\Merger;
use Hr\InputFilter\MergerFactory;
use Hr\Location\LocationService;
use Hr\Location\ZipCodeService;
use Hr\Log\QueueLoggerFactory;
use Hr\Map\MapService;
use Hr\Map\MapServiceFactory;
use Hr\Menu\MenuService;
use Hr\Menu\MenuServiceFactory;
use Hr\Module\ModuleService;
use Hr\Module\ModuleServiceFactory;
use Hr\Mvc\Controller\Plugin\ApiIdentity;
use Hr\Mvc\Controller\Plugin\ConfigPluginFactory;
use Hr\Mvc\Controller\Plugin\Guard;
use Hr\Mvc\Controller\Plugin\GuardFactory;
use Hr\Mvc\Controller\Plugin\JsonContent;
use Hr\Mvc\Controller\Plugin\PersonalizationPluginFactory;
use Hr\Personalization\PersonalizationService;
use Hr\RecordPicker\RecordPickerService;
use Hr\RecordPicker\RecordPickerServiceFactory;
use Hr\Service\AnalyticsService;
use Hr\Service\AnalyticsServiceFactory;
use Hr\Service\CronService;
use Hr\Service\ObjectManagerFactory;
use Hr\Service\PostCodeService;
use Hr\Service\PostCodeServiceFactory;
use Hr\Setting\SystemSettingsService;
use Hr\Setting\SystemSettingsServiceFactory;
use Hr\Table\TableService;
use Hr\Table\TableServiceFactory;
use Hr\View\Helper\{BreadcrumbsFactory,
    Checkbox,
    Config,
    ConfigFactory,
    Currency,
    DateTime,
    Dropdown as DropdownHelper,
    FieldOrder,
    FieldOrderFactory,
    FlashMessenger,
    FontAwesome,
    FormSl,
    HeadFactory,
    HeadLink,
    HeadScript,
    ImageElement as ImageElementHelper,
    IsAdmin,
    IsAdminFactory,
    IsAllowed,
    IsAllowedFactory,
    IsCustomerService,
    IsSubordinateOf,
    IsSubordinateOfFactory,
    MainMenu,
    Menu,
    MenuFactory,
    MobileDetect,
    ModuleActive,
    ModuleActiveFactory,
    Operations,
    OperationsFactory,
    OrdinalNumber,
    PageSummary,
    PaginationControlFactory,
    PermissionsHelper,
    PermissionsHelperFactory,
    PersonalizationHelper,
    PersonalizationHelperFactory,
    QueryParams,
    QueryParamsFactory,
    RecordPicker as RecordPickerHelper,
    SearchFormHelper,
    TableHelper,
    TableHelperFactory,
    UrlParam,
    UrlParamFactory};
use Hr\View\Helper\Acl;
use Hr\View\Helper\AclFactory;
use Hr\View\Helper\Path;
use Hr\View\Helper\PathFactory;
use Laminas\Authentication\AuthenticationService as LaminasAuthenticationService;
use Laminas\Cache\Storage\Adapter\AbstractAdapter;
use Laminas\Mvc\Controller\LazyControllerAbstractFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Laminas\View\Helper\Navigation\Breadcrumbs;
use Laminas\View\Helper\PaginationControl;

return [
    'router' => [
        'routes' => [
            'autocomplete' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/autocomplete',
                    'defaults' => [
                        'controller' => AutocompleteController::class,
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'dictionary' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/dictionary/:dictionary',
                            'defaults' => [
                                'action' => 'dictionary',
                            ],
                        ],
                    ],
                    'dependant-values' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/dependant-values/:dictionary',
                            'defaults' => [
                                'action' => 'dependant-values',
                            ],
                        ],
                    ],
                    'payer' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/payer/',
                            'defaults' => [
                                'action' => 'payer',
                            ],
                        ],
                    ],
                    'product' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/product',
                            'defaults' => [
                                'action' => 'product',
                            ],
                        ],
                    ],
                    'customer' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/customer',
                            'defaults' => [
                                'action' => 'customer',
                            ],
                        ],
                    ],
                ],
            ],
            'log' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/log/:action[/:type]',
                    'defaults' => [
                        'controller' => ActivityLogController::class,
                    ],
                ],
            ],
            'cron' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/cron[/:action]',
                    'defaults' => [
                        'controller' => CronController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            'InstanceAdapterFactory' => InstanceAdapterFactory::class,
            'Hr\Cache\Redis' => RedisFactory::class,
            AuthenticationService::class => AuthenticationServiceFactory::class,
            AuthorizationService::class => AuthorizationServiceFactory::class,
            LaminasAuthenticationService::class => InvokableFactory::class,
            PersonalizationService::class => InvokableFactory::class,
            AbstractAdapter::class => CacheStorageFactory::class,
            LocationService::class => InvokableFactory::class,
            SystemSettingsService::class => SystemSettingsServiceFactory::class,
            AclService::class => AclServiceFactory::class,
            MenuService::class => MenuServiceFactory::class,
            FormService::class => FormServiceFactory::class,
            TableService::class => TableServiceFactory::class,
            FileService::class => FileServiceFactory::class,
            ModuleService::class => ModuleServiceFactory::class,
            RecordPickerService::class => RecordPickerServiceFactory::class,
            HrDatabaseLoader::class => HrDatabaseLoaderFactory::class,
            DictionaryService::class => DictionaryServiceFactory::class,
            MapService::class => MapServiceFactory::class,
            ZipCodeService::class => ObjectManagerFactory::class,
            Merger::class => MergerFactory::class,
            AnalyticsService::class => AnalyticsServiceFactory::class,
            PostCodeService::class => PostCodeServiceFactory::class,
            FilesystemCache::class => FilesystemFactory::class,
            CronService::class => ReflectionBasedAbstractFactory::class,
            'QueueLogger' => QueueLoggerFactory::class,
        ],
        'aliases' => [
            ObjectManager::class => 'doctrine.entitymanager.orm_default',
            EntityManagerInterface::class => 'doctrine.entitymanager.orm_default',
        ],
        'abstract_factories' => [
            ReflectionBasedAbstractFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            AutocompleteController::class => AutocompleteControllerFactory::class,
            ActivityLogController::class => ActivityLogControllerFactory::class,
            CronController::class => LazyControllerAbstractFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            RecordPicker::class => InvokableFactory::class,
            Dropdown::class => InvokableFactory::class,
        ],
        'aliases' => [
            'recordPicker' => RecordPicker::class,
            'dropdown' => Dropdown::class,
        ],
    ],
    'view_helpers' => [
        'factories' => [
            SearchFormHelper::class => InvokableFactory::class,
            FlashMessenger::class => InvokableFactory::class,
            DateTime::class => InvokableFactory::class,
            OrdinalNumber::class => InvokableFactory::class,
            Checkbox::class => InvokableFactory::class,
            RecordPickerHelper::class => InvokableFactory::class,
            DropdownHelper::class => InvokableFactory::class,
            PermissionsHelper::class => PermissionsHelperFactory::class,
            PersonalizationHelper::class => PersonalizationHelperFactory::class,
            TableHelper::class => TableHelperFactory::class,
            UrlParam::class => UrlParamFactory::class,
            QueryParams::class => QueryParamsFactory::class,
            ModuleActive::class => ModuleActiveFactory::class,
            MainMenu::class => ModuleActiveFactory::class,
            Breadcrumbs::class => BreadcrumbsFactory::class,
            Operations::class => OperationsFactory::class,
            FieldOrder::class => FieldOrderFactory::class,
            ImageElementHelper::class => InvokableFactory::class,
            Path::class => PathFactory::class,
            Menu::class => MenuFactory::class,
            Acl::class => AclFactory::class,
            HeadScript::class => HeadFactory::class,
            HeadLink::class => HeadFactory::class,
            IsAdmin::class => IsAdminFactory::class,
            IsCustomerService::class => IsAdminFactory::class,
            FormSl::class => InvokableFactory::class,
            IsAllowed::class => IsAllowedFactory::class,
            PageSummary::class => InvokableFactory::class,
            Currency::class => InvokableFactory::class,
            IsSubordinateOf::class => IsSubordinateOfFactory::class,
            PaginationControl::class => PaginationControlFactory::class,
            Config::class => ConfigFactory::class,
            FontAwesome::class => InvokableFactory::class,
            MobileDetect::class => InvokableFactory::class,
        ],
        'invokables' => [
            'table' => TableHelper::class,
        ],
        'aliases' => [
            'searchForm' => SearchFormHelper::class,
            'fm' => FlashMessenger::class,
            'dt' => DateTime::class,
            'ord' => OrdinalNumber::class,
            'chk' => Checkbox::class,
            'formRecordPicker' => RecordPickerHelper::class,
            'formrecordpicker' => RecordPickerHelper::class,
            'form_record_picker' => RecordPickerHelper::class,
            'formDropdown' => DropdownHelper::class,
            'formdropdown' => DropdownHelper::class,
            'form_dropdown' => DropdownHelper::class,
            'permissions' => PermissionsHelper::class,
            'personalization' => PersonalizationHelper::class,
            'mainMenu' => MainMenu::class,
            'imageForm' => ImageElementHelper::class,
            'tikrowMenu' => Menu::class,
            'table' => TableHelper::class,
            'urlParam' => UrlParam::class,
            'queryParams' => QueryParams::class,
            'moduleActive' => ModuleActive::class,
            'breadcrumbs' => Breadcrumbs::class,
            'operations' => Operations::class,
            'fieldOrder' => FieldOrder::class,
            'path' => Path::class,
            'acl' => Acl::class,
            'headScript' => HeadScript::class,
            'headLink' => HeadLink::class,
            'isAdmin' => IsAdmin::class,
            'formSl' => FormSl::class,
            'isAllowed' => IsAllowed::class,
            'pageSummary' => PageSummary::class,
            'isSubordinateOf' => IsSubordinateOf::class,
            'config' => Config::class,
            'isCustomerService' => IsCustomerService::class,
            'curr' => Currency::class,
            'fontAwesome' => FontAwesome::class,
            'mobileDetect' => MobileDetect::class,
        ],
    ],
    'controller_plugins' => [
        'factories' => [
            'personalization' => PersonalizationPluginFactory::class,
            'config' => ConfigPluginFactory::class,
            ApiIdentity::class => InvokableFactory::class,
            JsonContent::class => InvokableFactory::class,
            Guard::class => GuardFactory::class,
        ],
        'aliases' => [
            'apiIdentity' => ApiIdentity::class,
            'jsonContent' => JsonContent::class,
            'guard' => Guard::class,
        ],
    ],
    'cache' => [
        'adapter' => 'Filesystem',
        'options' => [
            'cache_dir' => getcwd() . '/data/cache',
            'ttl' => '3600',
        ],
        'plugins' => [
            [
                'name' => 'Serializer',
                'options' => [],
            ],
            [
                'name' => 'exception_handler',
                'options' => [
                    'throw_exceptions' => false,
                ],
            ],
        ],
    ],
    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'template_map' => [
            'paginator' => __DIR__ . '/../view/partial/paginator.phtml',
            'sort' => __DIR__ . '/../view/partial/sort.phtml',
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
        'cache' => [
            'redis' => [
                'namespace' => 'Hr_Doctrine',
                'instance' => 'Hr\Cache\Redis',
            ],
        ],
    ],
    'twbbundle' => [
        // update tu vv twbs
        'class_map' => [
            RecordPicker::class => RecordPickerHelper::class,
            Dropdown::class => DropdownHelper::class,
            ImageElement::class => ImageElementHelper::class,
        ],
    ],
    'translator' => [
        'remote_translation' => [
            ['type' => HrDatabaseLoader::class],
        ],
    ],
    'translator_plugins' => [
        'factories' => [
            HrDatabaseLoader::class => HrDatabaseLoaderFactory::class,
        ],
    ],
    'twbshelper' => [
        'type_map' => [
            'recordpicker' => 'formrecordpicker',
            'dropdown' => 'formdropdown',
            'image' => 'formimage',
        ],
        'class_map' => [
            RecordPicker::class => 'formrecordpicker',
            Dropdown::class => 'formdropdown',
            ImageElement::class => 'formimage',
        ],
    ],
];
