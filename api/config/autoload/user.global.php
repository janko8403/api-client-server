<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

use Application\Authentication\AuthenticationPostListener;
use Application\Authentication\AuthenticationPostListenerFactory;
use Doctrine\DBAL\Driver\PDO\MySQL\Driver;
use DoctrineExtensions\Query\Mysql;
use Hr\View\Helper\ImageElement;
use Laminas\ApiTools\Admin\InputFilter\RpcService\PostInputFilter as RpcPostInputFilter;
use Laminas\Form\View\Helper\FormDate;
use Laminas\View\Helper\Placeholder;

return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => Driver::class,
                'params' => [
                    'host' => $_ENV['DB_HOST'],
                    'dbname' => $_ENV['DB_NAME'],
                    'port' => $_ENV['DB_PORT'],
                    'user' => $_ENV['DB_USER'],
                    'password' => $_ENV['DB_PASSWORD'],
                    'charset' => 'utf8',
                ],
            ],
            'configuration' => [
                'orm_default' => [
                    'query_cache' => 'filesystem',
                    'result_cache' => 'filesystem',
                    'metadata_cache' => 'filesystem',
                    'hydration_cache' => 'filesystem',
                ],
            ],
        ],
        'configuration' => [
            'orm_default' => [
                'string_functions' => [
                    'Rand' => Mysql\Rand::class,
                    'Now' => Mysql\Now::class,
                    'Date' => Mysql\Date::class,
                    'Month' => Mysql\Month::class,
                    'Year' => Mysql\Year::class,
                    'Quarter' => Mysql\Quarter::class,
                    'Charlen' => Mysql\CharLength::class,
                    'Week' => Mysql\Week::class,
                    'If' => Mysql\IfElse::class,
                    'DayOfWeek' => Mysql\DayOfWeek::class,
                    'Power' => Mysql\Power::class,
                    'IfNull' => Mysql\IfNull::class,
                ],
                'datetime_functions' => [
                    'TimestampDiff' => Mysql\TimestampDiff::class,
                    'DateDiff' => Mysql\DateDiff::class,
                ],
            ],
        ],
    ],
    'db' => [
        'charset' => 'utf8',
        'database' => $_ENV['DB_NAME'],
        'driver' => 'PDO_Mysql',
        'hostname' => $_ENV['DB_HOST'],
        'username' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
    ],
    'service_manager' => [
        'invokables' => [
            'mobileDetect' => Mobile_Detect::class,
        ],
        'factories' => [
            AuthenticationPostListener::class => AuthenticationPostListenerFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'formdate' => FormDate::class,
            'placeholder' => Placeholder::class,
            'formimage' => ImageElement::class,
        ],
    ],
    'paths' => [
        'monitoringThumbnailsMandantPath' => getcwd() . '/data/files/monitoringThumbnails',
        'monitoringQuestionThumbnailsMandantPath' => getcwd() . '/data/files/monitoringQuestionsThumbnails',
        'monitoringFulfillmentDocuments' => getcwd() . '/data/files/monitoringFulfillmentDocuments',
        'monitoringQuestionAnswerPhotos' => getcwd() . '/data/monitoringPhotos',
        'monitoringCreatedDocuments' => getcwd() . '/data/documents',
        'monitoringQuestionAnswerAttachments' => getcwd() . '/data/monitoringAttachments',
    ],
    'sms_api_token' => $_ENV['SMS_API_TOKEN'],
    'view_manager' => [
        'app_version' => 1,
        'display_not_found_reason' => false,
        'display_exceptions' => false,
        'css' => [
//            'tikrow.css',
        ],
        'title' => $_ENV['INSTANCE_TITLE'] ?? '',
        'favicon' => ($_ENV['INSTANCE_NAME'] ?? '') . '/favicon.ico',
        'custom_login_image' => ($_ENV['INSTANCE_NAME'] ?? '') . '/logo.png',
        'login_img_href' => '/',
        'logo' => ($_ENV['INSTANCE_NAME'] ?? '') . '/sygnet.png',
        'logo_small' => ($_ENV['INSTANCE_NAME'] ?? '') . '/sygnet.png',
    ],
    'google_api' => [
        'javascript' => 'xxx',
    ],
    'rollbar' => [
//        'access_token' => 'b8f73bfc6599473cacf8186bd2529891',
        'environment' => 'production',
    ],
    'api-tools-mvc-auth' => [
        'authentication' => [
            'adapters' => [
                'oauth2db' => [
                    'adapter' => 'Laminas\\ApiTools\\MvcAuth\\Authentication\\OAuth2Adapter',
                    'storage' => [
                        'adapter' => 'pdo',
                        'dsn' => sprintf('mysql:host=%s;dbname=%s', $_ENV['DB_HOST'], $_ENV['DB_NAME']),
                        'route' => '/oauth',
                        'username' => $_ENV['DB_USER'],
                        'password' => $_ENV['DB_PASSWORD'],
                    ],
                ],
            ],
        ],
    ],
    'analytics' => [
        'ua' => 'xx',
    ],
    'queue-length-check' => [
        'limit' => 100,
        'notifications' => [
            '501005539', // PZ
            '663804698', // MK
        ],
    ],
    'frontend_version' => 1,
    'frontend_url' => 'https://gielda.komfort.tikrow.pl',
    'api-tools-admin' => [
        'path_spec' => Laminas\ApiTools\Admin\Model\ModulePathSpec::PSR_4,
    ],
    'api-tools-configuration' => [
        'enable_short_array' => true,
        'class_name_scalars' => true,
    ],
    'input_filters' => [
        'aliases' => [
            'ZF\Apigility\Admin\InputFilter\RpcService\PostInputFilter' => RpcPostInputFilter::class,
        ],
    ],
    'notifications' => [
        'catch_all' => [
            'sms' => [],
            'email' => [],
        ],
    ],
    'mailgun' => [
        'endpoint' => $_ENV['MAILGUN_ENDPOINT'],
        'domain' => $_ENV['MAILGUN_DOMAIN'],
        'key' => $_ENV['MAILGUN_KEY'],
        'from' => $_ENV['MAILGUN_FROM'],
    ],
];