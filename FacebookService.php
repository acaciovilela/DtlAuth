<?php

namespace DtlFacebook\Service;

use DtlFacebook\Service\RequestService;
use Zend\Json\Json;

class FacebookService {
    
    const API_GRAPH_URI = 'https://graph.facebook.com/';
    
    const API_GRAPH_VERSION = 'v2.10/';
    
    /**
     * @var array
     */
    protected $configs;

    /**
     * @var RequestService
     */
    protected $requestService;
    
    /**
     * Get User profile information
     * 
     * @param string $token
     */
    public function getUser(string $token, string $userId = 'me') {
        $uri = self::API_GRAPH_URI . self::API_GRAPH_VERSION . $userId;
        $uri .= '?fields=id,name,about,birthday,cover,email,gender,hometown';
        $uri .= ',accounts,picture,permissions';
        $uri .= ',posts{id,application,created_time,description,icon,is_published,link,message,object_id,picture,properties,shares,source,type,likes.limit(10000){picture,name},comments.limit(10000){from{picture,name},message},attachments,place}';
        $uri .= '&access_token=' . $token;
        $response = $this->getRequestService()->request($uri);
        $result = Json::decode($response, Json::TYPE_ARRAY);
        if (key_exists('error', $result)) {
            return false;
        }
        return $result;
    }
    
    /**
     * 
     * Get Posts by User ID
     * 
     * @param string $token
     * @param string $userId
     * @return array
     */
    public function getPosts(string $token, string $userId = 'me') {
        $uri = self::API_GRAPH_URI . self::API_GRAPH_VERSION . $userId . '/posts';
        $uri .= '?access_token=' . $token;
        $uri .= '&fields=id,application,created_time,description,icon,is_published,link,message,object_id,picture,properties,shares,source,type,likes.limit(10000){picture,name},comments.limit(10000){from{picture,name},message},attachments,place';
        $result = $this->getRequestService()->request($uri);
        return Json::decode($result, Json::TYPE_ARRAY);
    }
    
    /**
     * 
     * Get post informations by Post ID
     * 
     * @param string $token
     * @param string $postId
     * @return array
     */
    public function getPost(string $token, string $postId) {
        $uri = self::API_GRAPH_URI . self::API_GRAPH_VERSION . $postId;
        $uri .= '?access_token=' . $token;
        $uri .= '&fields=id,application,caption,created_time,description,from,icon,is_published,link,message,message_tags,object_id,parent_id,picture,properties,shares,source,type';
        $result = $this->getRequestService()->request($uri);
        return Json::decode($result, Json::TYPE_ARRAY);
    }
    
    public function getConfigs() {
        return $this->configs;
    }

    public function getRequestService(): RequestService {
        return $this->requestService;
    }

    public function setConfigs($configs) {
        $this->configs = $configs;
        return $this;
    }

    public function setRequestService(RequestService $requestService) {
        $this->requestService = $requestService;
        return $this;
    }

}
