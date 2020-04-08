<?php

namespace DtlAuth\Authentication\Adapters\Factory;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use DtlAuth\Authentication\Adapters\SicoobAdapter;

class SicoobAdapterFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $adapter = new SicoobAdapter();
        $adapter->setConfigs($container->get('config')['sicoob']);
        return $adapter;
    }

}
