<?php

namespace Catalog\Amazon;

class Request
{
    const SERVICE_VERSION = '2009-01-01';

    private $keys;
    private $url;
    private $key;

    const FAILOVER_TIMEOUT = 60;

    private $urls = [
        'CA' => 'http://webservices.amazon.ca/onca/xml',
        'CN' => 'http://webservices.amazon.cn/onca/xml',
        'DE' => 'http://webservices.amazon.de/onca/xml',
        'ES' => 'http://webservices.amazon.es/onca/xml',
        'FR' => 'http://webservices.amazon.fr/onca/xml',
        'IT' => 'http://webservices.amazon.it/onca/xml',
        'JP' => 'http://webservices.amazon.co.jp/onca/xml',
        'UK' => 'http://webservices.amazon.co.uk/onca/xml',
        'US' => 'http://webservices.amazon.com/onca/xml'
    ];

    private $proxies = array(
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

    public function perform($country, $params)
    {
        $this->url = $this->urls[$country];
        $this->key = $this->getRandomKey();
        $this->httpClient->getClient()->setProxy($this->getRandomProxy());
        return $this->httpClient->get($this->url . '?' . http_build_query($this->getQueryParams($params)));
    }

    public function performFailsafe($country, $params)
    {
        while (true) {
            try {
                return $this->perform($country, $params);
            } catch (\Buzz\Exception\ClientException $e) {
                sleep(self::FAILOVER_TIMEOUT);
            }
        }
    }

    private function getRandomKey()
    {
        return $this->keys[array_rand($this->keys)];
    }

    private function getRandomProxy()
    {
        return $this->proxies[array_rand($this->proxies)];
    }

    private function getQueryParams($params)
    {
        $params = array_merge($params, [
            'AWSAccessKeyId' => $this->key['public'],
            'Timestamp' => (new \DateTime('now', new \DateTimeZone('UTC')))->format(DATE_ISO8601),
            'Version' => self::SERVICE_VERSION,
            'SignatureVersion' => '2',
            'SignatureMethod' => 'HmacSHA256',
            'AssociateTag' => 'mytag-20'
        ]);
        $params['Signature'] = $this->createSignature($params);
        return $params;
    }

    private function createSignature($params)
    {
        $parsedUrl = parse_url($this->url);
        $data = "GET\n" . $parsedUrl['host'] . "\n" . $parsedUrl['path'] . "\n";
        uksort($params, 'strcmp');
        $data .= $this->getParametersAsString($params);
        return $this->sign($data);
    }

    private function getParametersAsString(array $params)
    {
        $queryParameters = array();
        foreach ($params as $key => $value) {
            $queryParameters[] = $key . '=' . $this->urlencode($value);
        }
        return implode('&', $queryParameters);
    }

    private function urlencode($value) {
        return str_replace('%7E', '~', rawurlencode($value));
    }

    private function sign($data)
    {
        return base64_encode(hash_hmac('sha256', $data, $this->key['private'], true));
    }
}