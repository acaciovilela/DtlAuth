<?php

namespace DtlAuth\View\Helper\Factory;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use DtlAuth\Service\OAuth2Service;
use DtlAuth\View\Helper\FacebookSignInBtn;
use DtlAuth\Authentication\Adapters\FacebookAdapter;

class FacebookSignInBtnFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $helper = new FacebookSignInBtn();
        $helper->setOauthService(new OAuth2Service($container->get(FacebookAdapter::class)));
        return $helper;
    }

}
