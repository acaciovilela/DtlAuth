<?php

namespace DtlAuth\Controller\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use DtlAuth\Controller\OAuthController;
use DtlAuth\Service\OAuth2Service;

class OAuthControllerFactory implements FactoryInterface {
    
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $controller = new OAuthController();
        return $controller;
    }
}