<?php

/**
 * Sicoob Adapter
 */

namespace DtlAuth\Authentication\Adapters;

use DtlAuth\Authentication\OAuth2AdapterInterface;
use DtlAuth\Authentication\OAuth2AdapterAbstract;

class SicoobAdapter extends OAuth2AdapterAbstract implements OAuth2AdapterInterface {

    /**
     * Sicoob host
     */
    const API_AUTHORIZE_HOST = 'https://sandbox.sicoob.com.br';

    /**
     * Sicoob authorize path
     */
    const API_AUTHORIZE_PATH = '/oauth2/authorize';

    /**
     * Graph API base URI
     */
    const API_BASE_URI = 'https://sandbox.sicoob.com.br/';

    /**
     * Access Token path
     */
    const API_ACCESS_TOKEN_PATH = '/token';

    /**
     * Default response type for authorize request
     */
    const RESPONSE_TYPE = 'code';

    /**
     * Set and Get Sicoob scopes as a string
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

        $uri = self::API_AUTHORIZE_HOST . self::API_AUTHORIZE_PATH;

        return $this->formatUri($uri);
    }

    /**
     * 
     * @return string
     */
    public function getAccessTokenUri() {

        $uri = self::API_BASE_URI . self::API_ACCESS_TOKEN_PATH;

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
        $params['cooperativa'] = $this->getCooperativa('cooperativa');
        $params['contaCorrente'] = $this->getContaCorrente('contaCorrente');
        $params['versaoHash'] = 3;
        return $params;
    }

    /**
     * 
     * @param string $code
     * @return array
     */
    public function getAccessTokenParameters(string $code) {
        $params = [];
        $params['grant_type'] = 'authorization_code';
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

    public function getCooperativa(string $key) {
        return $this->getConfigByKey($key);
    }

    public function getContaCorrente(string $key) {
        return $this->getConfigByKey($key);
    }
    
}
