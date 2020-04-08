<?php

namespace DtlAuth\Service\Manager;

use Zend\ServiceManager\ServiceManager;
use DtlAuth\Authentication\Adapters\FacebookAdapter;
use DtlAuth\Service\Social\Facebook as FacebookService;
use DtlAuth\Entity\Facebook as FacebookEntity;
use DtlAuth\Service\OAuth2Service;

class FacebookManager {

    /**
     *
     * @var ServiceManager
     */
    protected $serviceManager;

    public function signIn(string $code, string $network, string $type) {
        
        $sm = $this->getServiceManager();
        
        $oauthService = new OAuth2Service($sm->get(FacebookAdapter::class));
        
        $accessToken = $oauthService->getAccessToken($code);

        /**
         * Check Access Token
         */
        $app = $oauthService->getAccessToken('', ['grant_type' => 'client_credentials']);

        $check = false;
        if (key_exists('access_token', $app)) {
            $facebookService = $sm->get(FacebookService::class);
            $check = $facebookService->checkAccessToken($accessToken['access_token'], $app['access_token']);
        }

        if (is_array($check)) {
            if (key_exists('data', $check)) {
                $faceUserId = $check['data']['user_id'];
            }
        }

        /**
         * Check if token belongs to profile of a connected user
         */
        $em = $sm->get('doctrine.entitymanager.orm_default');
        
        $connected = $em->getRepository(FacebookEntity::class)
                ->findOneBy(['faceUserId' => $faceUserId]);

        if (!$connected) {
            $authService = $sm->get(\Zend\Authentication\AuthenticationService::class);
            $identity = $authService->getIdentity();

            $user = $em->getRepository(\DtlUser\Entity\User::class)
                    ->find($identity->getId());

            $facebook = new FacebookEntity();
            $facebook->setAccessToken($accessToken['access_token']);
            $facebook->setTokenType($accessToken['token_type']);

            if (key_exists('expires_in', $accessToken)) {
                $facebook->setExpiresIn($accessToken['expires_in']);
            }

            $facebook->setFaceUserId($faceUserId);
            $facebook->setUser($user);
            $em->persist($facebook);
            $em->flush();
        } else {
            $connected->setAccessToken($accessToken['access_token']);
            if (key_exists('expires_in', $accessToken)) {
                $connected->setExpiresIn($accessToken['expires_in']);
            }
            $em->persist($connected);
            $em->flush();
        }
        return true;
    }

    public function getServiceManager(): ServiceManager {
        return $this->serviceManager;
    }

    public function setServiceManager(ServiceManager $serviceManager) {
        $this->serviceManager = $serviceManager;
        return $this;
    }

}
