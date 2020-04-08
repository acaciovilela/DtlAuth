<?php

namespace DtlAuth\Service\Social\Factory;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use DtlAuth\Service\Social\Facebook;
use DtlAuth\Service\RequestService;

class FacebookFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $facebook = new Facebook();
        $facebook->setConfigs($container->get('config')['facebook']);
        $facebook->setRequest(new RequestService());
        return $facebook;
    }

}
