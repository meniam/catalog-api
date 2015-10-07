<?php

namespace Catalog\Taobao;

use Buzz\Browser;
use Buzz\Client\Curl;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class Request
{
    private $proxy;

    public function __construct($proxy)
    {
        $this->proxy = $proxy;
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new GetSetMethodNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    public function perform($url)
    {
        $curl = new Curl();
        $curl->setTimeout(30);
        $curl->setProxy($this->proxy);
        $browser = new Browser($curl);
        return $this->serializer->decode($browser->post($url)->getContent(), 'xml');
    }
}