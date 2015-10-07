<?php

namespace Catalog;

class Subrequest
{
    private $options = [
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1
    ];
    private $descriptor;

    public function __construct($method, $url, array $data)
    {
        $this->options[CURLOPT_URL] = $url;
        switch ($method) {
            case 'GET':
                break;
            case 'POST':
                $this->options[CURLOPT_POST] = 1;
                $this->options[CURLOPT_POSTFIELDS] = $data;
            default:
                $this->options[CURLOPT_CUSTOMREQUEST] = $method;
        }
    }

    public function withHeaders(array $headers)
    {
        $this->options[CURLOPT_HTTPHEADER] = $headers;
        return $this;
    }

    public function setTimeout($sec)
    {
        $this->options[CURLOPT_CONNECTTIMEOUT] = $sec;
        return $this;
    }

    public function viaProxy($proxyAddr)
    {
        $this->options[CURLOPT_PROXY] = $proxyAddr;
        return $this;
    }

    public function create()
    {
        $this->descriptor = curl_init();
        curl_setopt_array($this->descriptor, $this->options);
        return $this->descriptor;
    }

    public function getResult()
    {
        return curl_multi_getcontent($this->descriptor);
    }

    public function getDescriptor()
    {
        return $this->descriptor;
    }
}