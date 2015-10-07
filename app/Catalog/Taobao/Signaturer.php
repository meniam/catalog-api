<?php

namespace Catalog\Taobao;

class Signaturer
{
    const API_VERSION = '2.0';
    const PRODUCTION_URL = 'http://gw.api.taobao.com/router/rest?';

    private $publicKey;
    private $privateKey;

    public function __construct($publicKey, $privateKey)
    {
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
    }

    public function getUrl(array $options = [])
    {
        return self::PRODUCTION_URL . http_build_query($this->getParams($options));
    }

    private function getParams($options)
    {
        $options['app_key'] = $this->publicKey;
        $options['format'] = 'xml';
        $options['v'] = self::API_VERSION;
        $options['timestamp'] = date('Y-m-d H:i:s');
        $options['sign_method'] = 'md5';
        $options['sign'] = $this->createSignature($options);
        return $options;
    }

    private function createSignature($options)
    {
        ksort($options);
        $s = '';
        foreach ($options as $k=>$v) {
            $s .= $k . $v;
        }
        $s = $this->privateKey . $s . $this->privateKey;
        return strtoupper(md5($s));
    }
}
