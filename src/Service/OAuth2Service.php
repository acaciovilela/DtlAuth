<?php

namespace DtlAuth\Service;

use DtlAuth\Authentication\OAuth2AdapterInterface;

class OAuth2Service {

    const RESPONSE_TYPE_DEFAULT = 'code';

    /**
     * 
     * @var string
     */
    protected $clientId;

    /**
     *
     * @var string
     */
    protected $clientSecret;

    /**
     *
     * @var string
     */
    protected $redirectUri;

    /**
     *
     * @var string
     */
    protected $responseType;

    /**
     *
     * @var string
     */
    protected $grantType;

    /**
     * One or more scope values indicating which parts of the user's account 
     * you wish to access
     * 
     * @var array
     */
    protected $scope;

    /**
     * This is a md5 string formed by the follow statement
     *      
     * md5(microtime() . rand())
     * 
     * Generating a unique key to be used like only validation of request
     * 
     * @var string 
     */
    protected $state;

    /**
     *
     * @var string
     */
    protected $authorizeUri;

    /**
     *
     * @var string
     */
    protected $accessTokenUri;

    /**
     *
     * @var string
     */
    protected $exchangeTokenUri;

    /**
     *
     * @var array
     */
    protected $params;

    /**
     *
     * @var OAuth2AdapterInterface
     */
    protected $adapter;

    /**
     * 
     * @param OAuth2AdapterInterface $adapter
     */
    public function __construct(OAuth2AdapterInterface $adapter = null) {
        $this->adapter = $adapter;
    }

    /**
     * 
     * @param bool $useState
     * @return string
     * @throws \Exception
     */
    public function getAuthorizationUrl(bool $useState = false) {

        if ($this->getAdapter()) {

            $baseUrl = $this->getAdapter()->getAuthorizeUri();

            $params = $this->getAdapter()->getAuthorizeParameters();

            if ($useState) {
                $params['state'] = $this->getState();
            }

            $uri = implode('?', [$baseUrl, $this->createHttpQuery($params)]);
        } else {

            if (null === $this->getAuthorizeUri()) {
                throw new \Exception('Authorization URL is not defined!');
            }

            $parsedUri = parse_url($this->getAuthorizeUri());

            $uri_scheme = strtolower($parsedUri['scheme']);
            $uri_host = strtolower($parsedUri['host']);
            $uri_path = strtolower($parsedUri['path']);

            $baseUrl = $uri_scheme . '://' . $uri_host . $uri_path;

            $params = [];

            if (null === $this->getClientId()) {
                throw new \Exception('Client ID is not defined!');
            }

            $params['client_id'] = $this->getClientId();

            if (null === $this->getResponseType()) {
                $this->responseType = self::RESPONSE_TYPE_DEFAULT;
            }

            $params['response_type'] = $this->getResponseType();

            if (null === $this->getRedirectUri()) {
                throw new \Exception('Redirect URI is not defined!');
            }

            $params['redirect_uri'] = $this->getRedirectUri();

            if ($useState) {
                $params['state'] = $this->getState();
            }

            if ($this->getScope()) {
                $params['scope'] = $this->getScope();
            }

            if (!empty($this->params) && is_array($this->params)) {
                foreach ($this->getParams() as $key => $param) {
                    $params[$key] = $param;
                }
            }

            $uri = implode('?', [$this->getAuthorizeUri(), $this->createHttpQuery($params)]);
        }

        return $uri;
    }

    /**
     * 
     * @param string $code
     */
    public function getAccessToken(string $code, array $parameters = [], bool $setHeader = false) {

        if ($this->getAdapter()) {

            $baseUrl = $this->getAdapter()->getAccessTokenUri();

            $params = $this->getAdapter()->getAccessTokenParameters($code);

            if (!empty($parameters)) {
                $params = array_merge($params, $parameters);
            }

            $request = new RequestService();

            if ($setHeader) {
                $request->setHeaders('Authorization: Basic MlZqWmJ5M3d6NHZpMWVWVGJYVmozenFSaDJjYTpaZGZMR2JmdGlJc3JEZW5MVWdZbXVadnFUY1lh');
            }

            $response = $request->request($baseUrl, 'POST', $params);
        } else {

            if (!isset($code)) {
                throw new \Exception('Code is not defined or invalid!');
            }

            $params['code'] = $code;

            if (null === $this->getClientId()) {
                throw new \Exception('Client ID is not defined!');
            }

            $params['client_id'] = $this->getClientId();

            if (null === $this->getClientSecret()) {
                throw new \Exception('Client secret is not defined!');
            }

            $params['client_secret'] = $this->getClientSecret();

            $params['grant_type'] = $this->getGrantType();

            if (null === $this->getRedirectUri()) {
                throw new \Exception('Redirect URI is not defined!');
            }

            $params['redirect_uri'] = $this->getRedirectUri();

            if (null === $this->getAccessTokenUri()) {
                throw new \Exception('Access Token URI is not defined!');
            }

            $uri = $this->getAccessTokenUri();

            $request = new RequestService();
            $response = $request->request($uri, 'POST', $params);
        }

        return $request->getJsonDecode($response);
    }

    /**
     * 
     * @param string $toke
     */
    public function getRefreshToken(string $token, array $parameters = [], bool $setHeader = false) {

        if ($this->getAdapter()) {

            $baseUrl = $this->getAdapter()->getAccessTokenUri();

            $params = $this->getAdapter()->getResfreshTokenParameters($token);

            if (!empty($parameters)) {
                $params = array_merge($params, $parameters);
            }

            $request = new RequestService();

            $response = $request->request($baseUrl, 'POST', $params);
        } else {

            if (null === $this->getClientId()) {
                throw new \Exception('Client ID is not defined!');
            }

            $params['client_id'] = $this->getClientId();

            if (null === $this->getClientSecret()) {
                throw new \Exception('Client secret is not defined!');
            }

            $params['client_secret'] = $this->getClientSecret();

            $params['grant_type'] = $this->getGrantType();

            if (null === $this->getAccessTokenUri()) {
                throw new \Exception('Access Token URI is not defined!');
            }

            $uri = $this->getAccessTokenUri();

            $request = new RequestService();

            $response = $request->request($uri, 'POST', $params);
        }

        return $request->getJsonDecode($response);
    }

    /**
     * 
     * @param array $params
     * @return string
     */
    public function createHttpQuery(array $params) {

        $pairs = [];

        uksort($params, 'strcmp');
        foreach ($params as $key => $value) {
            if (is_array($value)) {
                sort($value, SORT_STRING);
                foreach ($value as $duplicate) {
                    $pairs[] = rawurlencode($key) . '=' . rawurlencode($ducplicate);
                }
            } else {
                $pairs[] = rawurlencode($key) . '=' . rawurlencode($value);
            }
        }

        return implode('&', $pairs);
    }

    public function getClientId() {
        return $this->clientId;
    }

    public function getClientSecret() {
        return $this->clientSecret;
    }

    public function getRedirectUri() {
        return $this->redirectUri;
    }

    public function getResponseType() {
        return $this->responseType;
    }

    public function getGrantType() {
        if (!isset($this->grantType)) {
            $this->grantType = 'authorization_code';
        }
        return $this->grantType;
    }

    public function getScope() {
        return $this->scope;
    }

    public function getState() {
        if (!isset($this->state)) {
            $this->state = md5(microtime() . rand());
        }
        return $this->state;
    }

    public function getAuthorizeUri() {
        return $this->authorizeUri;
    }

    public function getExchangeTokenUri() {
        return $this->exchangeTokenUri;
    }

    public function setClientId($clientId) {
        $this->clientId = $clientId;
        return $this;
    }

    public function setClientSecret($clientSecret) {
        $this->clientSecret = $clientSecret;
        return $this;
    }

    public function setRedirectUri($redirectUri) {
        $this->redirectUri = $redirectUri;
        return $this;
    }

    public function setResponseType($responseType) {
        $this->responseType = $responseType;
        return $this;
    }

    public function setGrantType($grantType) {
        $this->grantType = $grantType;
        return $this;
    }

    public function setScope($scope) {
        $this->scope = $scope;
        return $this;
    }

    public function setState($state) {
        $this->state = $state;
        return $this;
    }

    public function setAuthorizeUri($authorizeUri) {
        $this->authorizeUri = $authorizeUri;
        return $this;
    }

    public function setExchangeTokenUri($exchangeTokenUri) {
        $this->exchangeTokenUri = $exchangeTokenUri;
        return $this;
    }

    public function getAccessTokenUri() {
        return $this->accessTokenUri;
    }

    public function setAccessTokenUri($accessTokenUri) {
        $this->accessTokenUri = $accessTokenUri;
        return $this;
    }

    public function getParams() {
        return $this->params;
    }

    public function setParams($params) {
        $this->params = $params;
        return $this;
    }

    public function getAdapter() {
        return $this->adapter;
    }

    public function setAdapter(OAuth2AdapterInterface $adapter) {
        $this->adapter = $adapter;
        return $this;
    }

}
