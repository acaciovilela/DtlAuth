<?php

namespace DtlAuth\Authentication;

interface OAuth2AdapterInterface {

    public function getAuthorizeUri();

    public function getResponseType();

    public function getAuthorizeParameters();

    public function getAccessTokenParameters(string $code);
}
