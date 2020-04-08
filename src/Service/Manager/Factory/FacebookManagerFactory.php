<?php

namespace DtlAuth\Service\Manager\Factory;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use DtlAuth\Service\Manager\FacebookManager;

class FacebookManagerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $facebookManager = new FacebookManager();
        $facebookManager->setServiceManager($container);
        return $facebookManager;
    }

}
