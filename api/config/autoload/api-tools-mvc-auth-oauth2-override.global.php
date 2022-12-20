<?php
/**
 * This file was autogenerated by laminas-api-tools/api-tools-mvc-auth (bin/api-tools-mvc-auth-oauth2-override.php),
 * and overrides the Laminas\ApiTools\OAuth2\Service\OAuth2Server to provide the ability to create named
 * OAuth2\Server instances.
 */

return [
    'service_manager' => [
        'factories' => [
            \Laminas\ApiTools\OAuth2\Service\OAuth2Server::class => \Laminas\ApiTools\MvcAuth\Factory\NamedOAuth2ServerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'oauth' => [
                'options' => [
                    'spec' => '%oauth%',
                    'regex' => '(?P<oauth>(/oauth))',
                ],
                'type' => 'regex',
            ],
        ],
    ],
    'api-tools-oauth2' => [
        'access_lifetime' => 2_678_400, // 1 month
        'options' => [
//            'always_issue_new_refresh_token' => true,
//            'use_jwt_access_tokens' => true,
        ],
    ],
];
