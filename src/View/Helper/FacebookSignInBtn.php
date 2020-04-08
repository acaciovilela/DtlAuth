<?php

namespace DtlAuth\View\Helper;

use Zend\View\Helper\AbstractHelper;
use DtlAuth\Service\OAuth2Service;

class FacebookSignInBtn extends AbstractHelper {

    /**
     * @var OAuth2Service
     */
    protected $oauthService;

    public function render() {
        $btn = '<a href="' . $this->getOauthService()->getAuthorizationUrl() . '" ';
        $btn .= 'class="btn btn-primary btn-block">';
        $btn .= '<i class="fa fa-facebook-official"></i> ';
        $btn .= 'Entre com Facebook';
        $btn .= '</a';
        return $btn;
    }

    public function getOauthService(): OAuth2Service {
        return $this->oauthService;
    }

    public function setOauthService(OAuth2Service $oauthService) {
        $this->oauthService = $oauthService;
        return $this;
    }

}
