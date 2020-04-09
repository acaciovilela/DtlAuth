<?php

namespace DtlAuth\Service;

use Laminas\Http\Client;
use Laminas\Http\Response;
use Laminas\Json\Json;

class RequestService extends Client {

    public function __construct($uri = null, $options = null) {
        parent::__construct($uri, $options);
    }

    public function request(string $uri, string $method = 'GET', array $params = []) {
        if ($uri) {
            parent::setUri($uri);
        }

        parent::setMethod($method);

        if (!empty($params)) {
            if ($method === 'GET') {
                parent::setParameterGet($params);
            } else {
                parent::setParameterPost($params);
            }
        }
        return parent::send();
    }
    
    public function getJsonDecode(Response $response, $type = Json::TYPE_ARRAY) {
        return Json::decode($response->getBody(), $type);
    }

}
