<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Controller\ForgottenPasswordController;
use Application\Controller\ForgottenPasswordControllerFactory;
use Application\Controller\IndexController;
use Application\Controller\IndexControllerFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'application' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/application[/:action]',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'remote-login' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/remote-login/:token',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'login' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/login',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action' => 'login',
                    ],
                ],
            ],
            'no-access' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/no-access',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action' => 'no-access',
                    ],
                ],
            ],
            'login-client' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/login-client',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action' => 'login',
                    ],
                ],
            ],
            'admin' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/admin',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action' => 'admin',
                    ],
                ],
            ],
            'registration' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/registration',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action' => 'registration',
                    ],
                ],
            ],
            'logout' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/logout',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action' => 'logout',
                    ],
                ],
            ],
            'get-code' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/get-code',
                    'defaults' => [
                        'controller' => ForgottenPasswordController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'reset-password' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/reset-password',
                    'defaults' => [
                        'controller' => ForgottenPasswordController::class,
                        'action' => 'change',
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [],
    ],
    'controllers' => [
        'factories' => [
            IndexController::class => IndexControllerFactory::class,
            ForgottenPasswordController::class => ForgottenPasswordControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => [
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'layout/menu' => __DIR__ . '/../view/partials/menu.phtml',
            'layout/login' => __DIR__ . '/../view/layout/login.phtml',
            'layout/header' => __DIR__ . '/../view/partials/header.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
            'triple-dot-menu' => __DIR__ . '/../view/partials/triple-dot-menu.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
