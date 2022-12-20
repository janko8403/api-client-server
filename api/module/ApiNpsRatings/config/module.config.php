<?php
return [
    'controllers' => [
        'factories' => [
            'ApiNpsRatings\\V1\\Rpc\\FetchAll\\Controller' => \ApiNpsRatings\V1\Rpc\FetchAll\FetchAllControllerFactory::class,
            'ApiNpsRatings\\V1\\Rpc\\Fetch\\Controller' => \ApiNpsRatings\V1\Rpc\Fetch\FetchControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'api-nps-ratings.rpc.fetch-all' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/api/nps',
                    'defaults' => [
                        'controller' => 'ApiNpsRatings\\V1\\Rpc\\FetchAll\\Controller',
                        'action' => 'fetchAll',
                    ],
                ],
            ],
            'api-nps-ratings.rpc.fetch' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/api/nps/:id',
                    'defaults' => [
                        'controller' => 'ApiNpsRatings\\V1\\Rpc\\Fetch\\Controller',
                        'action' => 'fetch',
                    ],
                ],
            ],
        ],
    ],
    'api-tools-versioning' => [
        'uri' => [
            0 => 'api-nps-ratings.rpc.fetch-all',
            1 => 'api-nps-ratings.rpc.fetch',
        ],
    ],
    'api-tools-rpc' => [
        'ApiNpsRatings\\V1\\Rpc\\FetchAll\\Controller' => [
            'service_name' => 'FetchAll',
            'http_methods' => [
                0 => 'GET',
            ],
            'route_name' => 'api-nps-ratings.rpc.fetch-all',
        ],
        'ApiNpsRatings\\V1\\Rpc\\Fetch\\Controller' => [
            'service_name' => 'Fetch',
            'http_methods' => [
                0 => 'GET',
            ],
            'route_name' => 'api-nps-ratings.rpc.fetch',
        ],
    ],
    'api-tools-content-negotiation' => [
        'controllers' => [
            'ApiNpsRatings\\V1\\Rpc\\FetchAll\\Controller' => 'Json',
            'ApiNpsRatings\\V1\\Rpc\\Fetch\\Controller' => 'Json',
        ],
        'accept_whitelist' => [
            'ApiNpsRatings\\V1\\Rpc\\FetchAll\\Controller' => [
                0 => 'application/vnd.api-nps-ratings.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'ApiNpsRatings\\V1\\Rpc\\Fetch\\Controller' => [
                0 => 'application/vnd.api-nps-ratings.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
        ],
        'content_type_whitelist' => [
            'ApiNpsRatings\\V1\\Rpc\\FetchAll\\Controller' => [
                0 => 'application/vnd.api-nps-ratings.v1+json',
                1 => 'application/json',
            ],
            'ApiNpsRatings\\V1\\Rpc\\Fetch\\Controller' => [
                0 => 'application/vnd.api-nps-ratings.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'api-tools-mvc-auth' => [
        'authorization' => [
            'ApiNpsRatings\\V1\\Rpc\\FetchAll\\Controller' => [
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
            'ApiNpsRatings\\V1\\Rpc\\Fetch\\Controller' => [
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
        ],
    ],
];
