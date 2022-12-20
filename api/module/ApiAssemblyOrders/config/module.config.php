<?php
return [
    'controllers' => [
        'factories' => [
            'ApiAssemblyOrders\\V1\\Rpc\\FetchAll\\Controller' => \ApiAssemblyOrders\V1\Rpc\FetchAll\FetchAllControllerFactory::class,
            'ApiAssemblyOrders\\V1\\Rpc\\Fetch\\Controller' => \ApiAssemblyOrders\V1\Rpc\Fetch\FetchControllerFactory::class,
            'ApiAssemblyOrders\\V1\\Rpc\\Accept\\Controller' => \ApiAssemblyOrders\V1\Rpc\Accept\AcceptControllerFactory::class,
            'ApiAssemblyOrders\\V1\\Rpc\\Hide\\Controller' => \ApiAssemblyOrders\V1\Rpc\Hide\HideControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            \ApiAssemblyOrders\V1\Service\AssemblyOrderService::class => \Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory::class,
            \ApiAssemblyOrders\V1\Service\HideService::class => \Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory::class,
            \ApiAssemblyOrders\V1\Service\AcceptService::class => \Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'api-assembly-orders.rpc.fetch-all' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/api/assembly-orders/:status',
                    'defaults' => [
                        'controller' => 'ApiAssemblyOrders\\V1\\Rpc\\FetchAll\\Controller',
                        'action' => 'fetchAll',
                    ],
                ],
            ],
            'api-assembly-orders.rpc.fetch' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/api/assembly-orders/:id',
                    'constraints' => [
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => 'ApiAssemblyOrders\\V1\\Rpc\\Fetch\\Controller',
                        'action' => 'fetch',
                    ],
                ],
            ],
            'api-assembly-orders.rpc.accept' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/api/assembly-orders/accept/:id',
                    'defaults' => [
                        'controller' => 'ApiAssemblyOrders\\V1\\Rpc\\Accept\\Controller',
                        'action' => 'accept',
                    ],
                ],
            ],
            'api-assembly-orders.rpc.hide' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/api/assembly-orders/hide/:id',
                    'defaults' => [
                        'controller' => 'ApiAssemblyOrders\\V1\\Rpc\\Hide\\Controller',
                        'action' => 'hide',
                    ],
                ],
            ],
        ],
    ],
    'api-tools-versioning' => [
        'uri' => [
            0 => 'api-assembly-orders.rpc.fetch-all',
            1 => 'api-assembly-orders.rpc.fetch',
            2 => 'api-assembly-orders.rpc.accept',
            3 => 'api-assembly-orders.rpc.hide',
        ],
    ],
    'api-tools-rpc' => [
        'ApiAssemblyOrders\\V1\\Rpc\\FetchAll\\Controller' => [
            'service_name' => 'FetchAll',
            'http_methods' => [
                0 => 'GET',
            ],
            'route_name' => 'api-assembly-orders.rpc.fetch-all',
        ],
        'ApiAssemblyOrders\\V1\\Rpc\\Fetch\\Controller' => [
            'service_name' => 'Fetch',
            'http_methods' => [
                0 => 'GET',
            ],
            'route_name' => 'api-assembly-orders.rpc.fetch',
        ],
        'ApiAssemblyOrders\\V1\\Rpc\\Accept\\Controller' => [
            'service_name' => 'Accept',
            'http_methods' => [
                0 => 'POST',
            ],
            'route_name' => 'api-assembly-orders.rpc.accept',
        ],
        'ApiAssemblyOrders\\V1\\Rpc\\Hide\\Controller' => [
            'service_name' => 'Hide',
            'http_methods' => [
                0 => 'POST',
            ],
            'route_name' => 'api-assembly-orders.rpc.hide',
        ],
    ],
    'api-tools-content-negotiation' => [
        'controllers' => [
            'ApiAssemblyOrders\\V1\\Rpc\\FetchAll\\Controller' => 'Json',
            'ApiAssemblyOrders\\V1\\Rpc\\Fetch\\Controller' => 'Json',
            'ApiAssemblyOrders\\V1\\Rpc\\Accept\\Controller' => 'Json',
            'ApiAssemblyOrders\\V1\\Rpc\\Hide\\Controller' => 'Json',
        ],
        'accept_whitelist' => [
            'ApiAssemblyOrders\\V1\\Rpc\\FetchAll\\Controller' => [
                0 => 'application/vnd.api-assembly-orders.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'ApiAssemblyOrders\\V1\\Rpc\\Fetch\\Controller' => [
                0 => 'application/vnd.api-assembly-orders.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'ApiAssemblyOrders\\V1\\Rpc\\Accept\\Controller' => [
                0 => 'application/vnd.api-assembly-orders.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'ApiAssemblyOrders\\V1\\Rpc\\Hide\\Controller' => [
                0 => 'application/vnd.api-assembly-orders.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
        ],
        'content_type_whitelist' => [
            'ApiAssemblyOrders\\V1\\Rpc\\FetchAll\\Controller' => [
                0 => 'application/vnd.api-assembly-orders.v1+json',
                1 => 'application/json',
            ],
            'ApiAssemblyOrders\\V1\\Rpc\\Fetch\\Controller' => [
                0 => 'application/vnd.api-assembly-orders.v1+json',
                1 => 'application/json',
            ],
            'ApiAssemblyOrders\\V1\\Rpc\\Accept\\Controller' => [
                0 => 'application/vnd.api-assembly-orders.v1+json',
                1 => 'application/json',
            ],
            'ApiAssemblyOrders\\V1\\Rpc\\Hide\\Controller' => [
                0 => 'application/vnd.api-assembly-orders.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'api-tools-mvc-auth' => [
        'authorization' => [
            'ApiAssemblyOrders\\V1\\Rpc\\FetchAll\\Controller' => [
                'actions' => [
                    'fetchAll' => [
                        'GET' => true,
                        'POST' => false,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ],
                ],
            ],
            'ApiAssemblyOrders\\V1\\Rpc\\Fetch\\Controller' => [
                'actions' => [
                    'fetch' => [
                        'GET' => true,
                        'POST' => false,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ],
                ],
            ],
            'ApiAssemblyOrders\\V1\\Rpc\\Accept\\Controller' => [
                'actions' => [
                    'accept' => [
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ],
                ],
            ],
            'ApiAssemblyOrders\\V1\\Rpc\\Hide\\Controller' => [
                'actions' => [
                    'hide' => [
                        'GET' => false,
                        'POST' => true,
                        'PUT' => false,
                        'PATCH' => false,
                        'DELETE' => false,
                    ],
                ],
            ],
        ],
    ],
];
