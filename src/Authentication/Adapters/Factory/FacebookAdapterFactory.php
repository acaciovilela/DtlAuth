<?php

namespace DtlAuth\Authentication\Adapters\Factory;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use DtlAuth\Authentication\Adapters\FacebookAdapter;

class FacebookAdapterFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $adapter = new FacebookAdapter();
        $adapter->setConfigs($container->get('config')['facebook']);
        return $adapter;
    }

}
