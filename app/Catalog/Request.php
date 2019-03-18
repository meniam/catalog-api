<?php

namespace Catalog;

class Request
{
    protected $keys;
    protected $key;

    protected $proxies = array (
        '188.68.0.213:8085',
        '83.171.253.251:8085',
        '188.68.0.149:8085',
        '188.72.127.57:8085',
        '37.44.252.170:8085',
        '93.179.90.57:8085',
        '85.202.195.174:8085',
        '37.44.253.83:8085',
        '188.68.3.177:8085',
        '46.161.61.236:8085',
        '83.171.253.163:8085',
        '85.202.195.25:8085',
        '185.46.84.191:8085',
        '46.161.61.169:8085',
        '83.171.253.167:8085',
        '85.202.194.23:8085',
        '188.72.96.77:8085',
        '185.89.100.84:8085',
        '5.8.47.64:8085',
        '83.171.252.53:8085',
        '5.101.219.142:8085',
        '185.46.84.144:8085',
        '188.72.127.164:8085',
        '94.158.22.23:8085',
        '95.85.71.55:8085',
        '212.115.51.23:8085',
        '95.181.176.241:8085',
        '79.110.28.77:8085',
        '5.8.47.92:8085',
        '5.188.216.112:8085',
        '5.62.159.228:8085',
        '46.161.61.112:8085',
        '95.85.69.105:8085',
        '5.101.219.161:8085',
        '91.204.14.204:8085',
    );

    public function __construct(\Buzz\Browser $httpClient, array $keys)
    {
        $this->httpClient = $httpClient;
        $this->keys = $keys;
    }

    public function prepare()
    {
        $this->key = $this->getRandomKey();
        $this->httpClient->getClient()->setProxy($this->getRandomProxy());
    }

    protected function getRandomKey()
    {
        return $this->keys[array_rand($this->keys)];
    }

    protected function getRandomProxy()
    {
        return $this->proxies[array_rand($this->proxies)];
    }
}