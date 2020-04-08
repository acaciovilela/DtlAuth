<?php

/**
 * Facebook Adapter
 */

namespace DtlAuth\Authentication\Adapters;

use DtlAuth\Authentication\OAuth2AdapterInterface;
use DtlAuth\Authentication\OAuth2AdapterAbstract;

class FacebookAdapter extends OAuth2AdapterAbstract implements OAuth2AdapterInterface {

    /**
     * Facebook host
     */
    const API_AUTHORIZE_HOST = 'https://www.facebook.com/';

    /**
     * Facebook authorize path
     */
    const API_AUTHORIZE_PATH = '/dialog/oauth';

    /**
     * Graph API base URI
     */
    const API_BASE_URI = 'https://graph.facebook.com/';

    /**
     * Access Token path
     */
    const API_ACCESS_TOKEN_PATH = '/oauth/access_token';

    /**
     * Default response type for authorize request
     */
    const RESPONSE_TYPE = 'code';

    /**
     * Set and Get Facebook scopes as a string
     * 
     * @var string
     */
    protected $scopes;

    /**
     * Get an authorize URI
     * 
     * @return string
     */
    public function getAuthorizeUri() {

        $uri = self::API_AUTHORIZE_HOST . $this->configs['api_version'] . self::API_AUTHORIZE_PATH;

        if (isset($this->configs['scope'])) {
            $this->setScopes($this->configs['scope']);
        }

        return $this->formatUri($uri);
    }

    /**
     * 
     * @return string
     */
    public function getAccessTokenUri() {

        $uri = self::API_BASE_URI . $this->configs['api_version'] . self::API_ACCESS_TOKEN_PATH;
        
        return $this->formatUri($uri);
    }

    /**
     * 
     * @return string
     */
    public function getResponseType() {
        return self::RESPONSE_TYPE;
    }

    /**
     * 
     * @param string $key
     * @return string
     */
    public function getClientId(string $key) {
        return $this->getConfigByKey($key);
    }

    /**
     * 
     * @param string $key
     * @return string
     */
    public function getClientSecret(string $key) {
        return $this->getConfigByKey($key);
    }

    /**
     * 
     * @param string $key
     * @return string
     */
    public function getRedirectUri(string $key) {
        return $this->getConfigByKey($key);
    }

    /**
     * 
     * @return array
     */
    public function getAuthorizeParameters() {
        $params = [];
        $params['client_id'] = $this->getClientId('client_id');
        $params['redirect_uri'] = $this->getRedirectUri('redirect_uri');
        $params['response_type'] = $this->getResponseType();
        $params['auth_type'] = 'reauthenticate';
        if (!empty($this->getScopes())) {
            $params['scope'] = $this->getScopes();
        }
        return $params;
    }

    /**
     * 
     * @param string $code
     * @return array
     */
    public function getAccessTokenParameters(string $code) {
        $params = [];
        $params['client_id'] = $this->getClientId('client_id');
        $params['client_secret'] = $this->getClientId('client_secret');
        $params['redirect_uri'] = $this->getRedirectUri('redirect_uri');
        $params['code'] = $code;
        return $params;
    }

    /**
     * 
     * @return string
     */
    public function getScopes() {
        return $this->scopes;
    }

    /**
     * 
     * @param string $scopes
     * @return $this
     */
    public function setScopes($scopes) {
        $this->scopes = $scopes;
        return $this;
    }

}
