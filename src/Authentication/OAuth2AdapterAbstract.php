<?php

namespace DtlAuth\Authentication;

abstract class OAuth2AdapterAbstract {

    /**
     *
     * @var array
     */
    protected $configs;

    public function formatUri(string $uri) {

        $parser = parse_url($uri);

        $uri_scheme = strtolower($parser['scheme']);
        
        $uri_host = strtolower($parser['host']);
        
        $uri_path = strtolower($parser['path']);

        $authorizeUri = $uri_scheme . '://' . $uri_host . $uri_path;

        return $authorizeUri;
    }
    
    abstract public function getResponseType();
    
    public function getConfigByKey(string $key) {
        if (!key_exists($key, $this->getConfigs())) {
            throw new \Exception('\"' . $key . '\" key is not defined at configuration module. !');
        }
        return $this->getConfigs()[$key];
    }

    public function getConfigs() {
        return $this->configs;
    }

    public function setConfigs($configs) {
        $this->configs = $configs;
        return $this;
    }

}
