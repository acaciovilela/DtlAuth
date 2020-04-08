<?php

namespace DtlAuth\Service\Social;

use DtlAuth\Service\RequestService;

class Facebook {

    /**
     *
     * @var array
     */
    protected $configs;

    /**
     *
     * @var RequestService
     */
    protected $request;

    /**
     * 
     * @param string $accessToken
     * @param string $appToken
     * @return array
     */
    public function checkAccessToken(string $accessToken, string $appToken) {
        $uri = $this->configs['api_base_uri'] . 'debug_token';
        $params = [
            'input_token' => $accessToken,
            'access_token' => $appToken,
        ];
        $response = $this->getRequest()->request($uri, 'GET', $params);
        return $this->getRequest()->getJsonDecode($response);
    }

    /**
     * Get User profile information
     * 
     * @param string $accessToken
     */
    public function getUser(string $accessToken, string $userId = 'me') {
        $uri = $this->configs['api_base_uri'] . $this->configs['api_version'] . '/' . $userId;
        $uri .= '?fields=id,name,about,birthday,cover,email,gender,hometown';
        $uri .= ',accounts,picture,permissions';
        $uri .= ',posts{id,application,created_time,description,icon,is_published,link,message,object_id,picture,properties,shares,source,type,likes.limit(10000){picture,name},comments.limit(10000){from{picture,name},message},attachments,place}';
        $uri .= '&access_token=' . $accessToken;
        $response = $this->getRequest()->request($uri);
        return $this->getRequest()->getJsonDecode($response);
    }

    public function getConfigs() {
        return $this->configs;
    }

    public function setConfigs($configs) {
        $this->configs = $configs;
        return $this;
    }

    public function getRequest(): RequestService {
        return $this->request;
    }

    public function setRequest(RequestService $request) {
        $this->request = $request;
        return $this;
    }

}
