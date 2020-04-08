<?php

namespace DtlAuth;

use Zend\ServiceManager\Factory\InvokableFactory;

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
    /**
     * Facebook configuration
     * 
     * Enter with your data for each field
     */
    'facebook' => [
        /**
         * Facebook Client ID
         */
        'client_id' => '2354348601273813',
        /**
         * Facebook Client Secret
         */
        'client_secret' => '75107d8887315c4f2d790a4085ac0b45',
        /**
         * Facebook Graph API base URI
         */
        'api_base_uri' => 'https://graph.facebook.com/',
        /**
         * Current API version 
         */
        'api_version' => 'v3.2',
        /**
         * Redirect URI to your application
         */
        'redirect_uri' => 'https://dartalla.com.br/oauth/facebook/profile',
        /**
         * Facebook scopes in authorize request
         */
        'scope' => '',
    ],
    'controllers' => [
        'factories' => [
            Controller\OAuthController::class => Controller\Factory\OAuthControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Authentication\Adapters\FacebookAdapter::class => Authentication\Adapters\Factory\FacebookAdapterFactory::class,
            Authentication\Adapters\SicoobAdapter::class => Authentication\Adapters\Factory\SicoobAdapterFactory::class,
            Service\Social\Facebook::class => Service\Social\Factory\FacebookFactory::class,
            Service\Manager\FacebookManager::class => Service\Manager\Factory\FacebookManagerFactory::class,
        ],
    ],
    'view_helpers' => [
        'factories' => [
            View\Helper\FacebookSignInBtn::class => View\Helper\Factory\FacebookSignInBtnFactory::class,
        ],
        'aliases' => [
            'facebookSignInBtn' => View\Helper\FacebookSignInBtn::class,
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
                'type' => \Zend\Router\Http\Segment::class,
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
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ]
];
