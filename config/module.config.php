<?php

namespace DtlAuth;

return [
    /**
     * DtlOAuth2 default configs
     */
    'oauth2' => [
        /**
         * The client ID you received when you created the application on server
         */
        'client_id' => '',
        /**
         * The client ID you received when you created the application on server
         * Since this request is made from server-side code, 
         * the secret is included
         */
        'client_secret' => '',
        /**
         * Indicates the URI to return the user to after authorization 
         * is complete, back to application
         */
        'redirect_uri' => '',
        /**
         * Indicates that your server expects to receive an authorization code
         * Defaul: 'code'
         */
        'response_type' => 'code',
        /**
         * OAuth 2 provides several "grant types" for different use cases. 
         * The grant types defined are:
         * 
         * [authorization_code, password, client_credentials, implicit]
         * 
         * Default: authorization_code
         */
        'grant_type' => 'authorization_code',
        /**
         * Set a authorize uri
         * 
         * Ex. https://api.example.com/autorize
         */
        'autorize_uri' => '',
        /**
         * Set a uri for exchange a code to an access token
         */
        'exchange_token_uri' => '',
    ],
    'controllers' => [
        'factories' => [
            Controller\OAuthController::class => Controller\Factory\OAuthControllerFactory::class,
        ],
    ],
    'access_filter' => [
        'options' => [
            'mode' => 'restrictive'
        ],
        'controllers' => [
            Controller\OAuthController::class => [
                ['actions' => ['oauth'], 'allow' => '*'],
                ['actions' => [], 'allow' => '@']
            ],
        ]
    ],
    'router' => [
        'routes' => [
            'dtl-oauth' => [
                'type' => \Laminas\Router\Http\Segment::class,
                'options' => [
                    'route' => '/oauth[/:network[/:type]]',
                    'constraints' => [
                        'network' => '[a-zA-Z0-9_-]*',
                        'type' => '[a-z-A-Z0-9-_]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\OAuthController::class,
                        'action' => 'oauth',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [],
            ],
        ],
    ],
];
