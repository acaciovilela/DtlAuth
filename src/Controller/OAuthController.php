<?php

namespace DtlAuth\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use DtlAuth\Service\OAuth2Service;
use DtlAuth\Service\Manager\FacebookManager;

class OAuthController extends AbstractActionController {

    public function oauthAction() {

        $sm = $this->getEvent()->getApplication()->getServiceManager();

        $network = $this->params()->fromRoute('network');

        switch ($network) {
            case 'facebook':
                $code = $this->params()->fromQuery('code');
                $type = $this->params()->fromRoute('type');

                if (!$code) {
                    return ['error' => ['error_message' => 'Não foi possível receber o código de autorização do servidor requisitado!']];
                }

                $fbManager = $sm->get(FacebookManager::class);
                $fbManager->signIn($code, $network, $type);

                break;
            default:
                $oauthService = new OAuth2Service();
                break;
        }
        
        return $this->redirect()->toRoute('dtl-admin');
    }

}
