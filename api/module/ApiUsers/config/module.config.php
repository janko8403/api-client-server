<?php
return [
    'service_manager' => [
        'factories' => [
            \ApiUsers\V1\Rpc\Profile\ProfileService::class => \Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            \ApiUsers\V1\Rpc\Profile\ProfileController::class => \Laminas\Mvc\Controller\LazyControllerAbstractFactory::class,
            'ApiUsers\\V1\\Rpc\\ResetPassword\\Controller' => \ApiUsers\V1\Rpc\ResetPassword\ResetPasswordControllerFactory::class,
            'ApiUsers\\V1\\Rpc\\ChangePassword\\Controller' => \ApiUsers\V1\Rpc\ChangePassword\ChangePasswordControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'api-users.rpc.profile' => [
                'type' => \Laminas\Router\Http\Segment::class,
                'options' => [
                    'route' => '/api/users/profile',
                    'defaults' => [
                        'controller' => \ApiUsers\V1\Rpc\Profile\ProfileController::class,
                        'action' => 'profile',
                    ],
                ],
            ],
            'api-users.rpc.reset-password' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/api/users/reset-password',
                    'defaults' => [
                        'controller' => 'ApiUsers\\V1\\Rpc\\ResetPassword\\Controller',
                        'action' => 'resetPassword',
                    ],
                ],
            ],
            'api-users.rpc.change-password' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/api/users/change-password',
                    'defaults' => [
                        'controller' => 'ApiUsers\\V1\\Rpc\\ChangePassword\\Controller',
                        'action' => 'changePassword',
                    ],
                ],
            ],
        ],
    ],
    'api-tools-versioning' => [
        'uri' => [
            0 => 'api-users.rpc.profile',
            1 => 'api-users.rpc.reset-password',
            2 => 'api-users.rpc.change-password',
        ],
    ],
    'api-tools-rpc' => [
        \ApiUsers\V1\Rpc\Profile\ProfileController::class => [
            'service_name' => 'Profile',
            'http_methods' => [
                0 => 'GET',
            ],
            'route_name' => 'api-users.rpc.profile',
        ],
        'ApiUsers\\V1\\Rpc\\ResetPassword\\Controller' => [
            'service_name' => 'ResetPassword',
            'http_methods' => [
                0 => 'POST',
            ],
            'route_name' => 'api-users.rpc.reset-password',
        ],
        'ApiUsers\\V1\\Rpc\\ChangePassword\\Controller' => [
            'service_name' => 'ChangePassword',
            'http_methods' => [
                0 => 'POST',
            ],
            'route_name' => 'api-users.rpc.change-password',
        ],
    ],
    'api-tools-content-negotiation' => [
        'controllers' => [
            \ApiUsers\V1\Rpc\Profile\ProfileController::class => 'Json',
            'ApiUsers\\V1\\Rpc\\ResetPassword\\Controller' => 'Json',
            'ApiUsers\\V1\\Rpc\\ChangePassword\\Controller' => 'Json',
        ],
        'accept_whitelist' => [
            \ApiUsers\V1\Rpc\Profile\ProfileController::class => [
                0 => 'application/vnd.api-users.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'ApiUsers\\V1\\Rpc\\ResetPassword\\Controller' => [
                0 => 'application/vnd.api-users.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'ApiUsers\\V1\\Rpc\\ChangePassword\\Controller' => [
                0 => 'application/vnd.api-users.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
        ],
        'content_type_whitelist' => [
            \ApiUsers\V1\Rpc\Profile\ProfileController::class => [
                0 => 'application/vnd.api-users.v1+json',
                1 => 'application/json',
            ],
            'ApiUsers\\V1\\Rpc\\ResetPassword\\Controller' => [
                0 => 'application/vnd.api-users.v1+json',
                1 => 'application/json',
            ],
            'ApiUsers\\V1\\Rpc\\ChangePassword\\Controller' => [
                0 => 'application/vnd.api-users.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'api-tools-mvc-auth' => [
        'authorization' => [
            \ApiUsers\V1\Rpc\Profile\ProfileController::class => [
                'actions' => [
                    'profile' => [
                        'GET' => true,
                        'POST' => false,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ],
                    'profileController' => [
                        'GET' => true,
                        'POST' => false,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ],
                ],
            ],
        ],
    ],
    'api-tools-content-validation' => [
        'ApiUsers\\V1\\Rpc\\ResetPassword\\Controller' => [
            'input_filter' => 'ApiUsers\\V1\\Rpc\\ResetPassword\\Validator',
        ],
        'ApiUsers\\V1\\Rpc\\ChangePassword\\Controller' => [
            'input_filter' => 'ApiUsers\\V1\\Rpc\\ChangePassword\\Validator',
        ],
    ],
    'input_filter_specs' => [
        'ApiUsers\\V1\\Rpc\\ResetPassword\\Validator' => [
            0 => [
                'required' => true,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\EmailAddress::class,
                        'options' => [],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'email',
            ],
        ],
        'ApiUsers\\V1\\Rpc\\ChangePassword\\Validator' => [
            0 => [
                'required' => true,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'min' => '6',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'password',
            ],
            1 => [
                'required' => true,
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\Identical::class,
                        'options' => [
                            'token' => 'password',
                        ],
                    ],
                ],
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                        'options' => [],
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                        'options' => [],
                    ],
                ],
                'name' => 'confirm',
            ],
        ],
    ],
];
