<?php

namespace Catalog;

class Request
{
    protected $keys;
    protected $key;

    protected $proxies = array (
        '107.170.134.88:3128',
        '107.170.133.110:3128',
        '107.170.133.107:3128',
        '107.170.133.113:3128',
        '107.170.133.114:3128',
        '107.170.133.105:3128',
        '107.170.133.106:3128',
        '107.170.133.111:3128',
        '107.170.133.104:3128',
        '107.170.133.109:3128',
        '107.170.134.188:3128',
        '107.170.134.245:3128',
        '107.170.133.112:3128',
        '107.170.133.115:3128',
        '107.170.133.103:3128'
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