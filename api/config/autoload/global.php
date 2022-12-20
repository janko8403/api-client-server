<?php
return [
    'asset_manager' => [
        'resolver_configs' => [
            'paths' => [
                0 => '/srv/hr/config/autoload/../asset/',
            ],
        ],
    ],
    'db' => [
        'adapters' => [
            'mysql' => [],
        ],
    ],
    'api-tools-mvc-auth' => [
        'authentication' => [
            'map' => [
                'ApiMonitorings\\V1' => 'oauth2db',
                'ApiFulfillments\\V1' => 'oauth2db',
                'ApiUsers\\V1' => 'oauth2db',
                'ApiAssemblyOrders\\V1' => 'oauth2db',
                'ApiNpsRatings\\V1' => 'oauth2db',
            ],
        ],
    ],
];
