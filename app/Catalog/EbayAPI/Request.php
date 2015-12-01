<?php

namespace Catalog\EbayAPI;

use Catalog\MultiRequest;

class Request extends \Catalog\Request
{
    private $params;

    const API_COMPATIBILITY_LEVEL = 863;
    const MAX_TIMEOUT = 40;

    public function perform($url, $params)
    {
        parent::prepare();
        $this->params = $params;
        $this->httpClient->getClient()->setTimeout(self::MAX_TIMEOUT);
        $query = str_replace('%25APP_ID%25', $this->key['appId'], http_build_query($params));

        if (!isset($params['siteId'])) {
            return $this->httpClient->get($url . $query);
        }

        return $this->httpClient->get($url . $query, $this->buildHeaders());
    }

    public function performXml($method, $url, $params)
    {
        parent::prepare();
        $this->params = $params;
        $this->httpClient->getClient()->setTimeout(self::MAX_TIMEOUT);

        $params['RequesterCredentials'] = array('eBayAuthToken' => $this->key['token']);

        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>' . "<{$method}Request/>");
        $xml->addAttribute('xmlns', 'urn:ebay:apis:eBLBaseComponents');
        self::arrayToXml($params, $xml);

        $this->params['callname'] = $method;

        return $this->httpClient->post($url, $this->buildHeaders(), $xml->asXml());
    }

    /**
     * Получить xml из масива
     *
     * @param array $array
     * @param \SimpleXMLElement $xml
     */
    public static function arrayToXml($array, &$xml) {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if (!is_numeric($key)) {
                    $subnode = $xml->addChild("$key");
                    self::arrayToXml($value, $subnode);
                } else {
                    self::arrayToXml($value, $xml);
                }
            } else {
                $xml->addChild("$key", "$value");
            }
        }
    }

    public function performParallel(array $requests)
    {
        $multirequest = new MultiRequest();
        $subrequests = [];
        foreach ($requests as $name=>$options) {
            $key = $this->getRandomKey();
            $query = str_replace('%25APP_ID%25', $key['appId'], http_build_query($options['params']));
            $subrequest = $multirequest->get($options['url'] . $query);
            if (isset($params['siteId'])) {
                $subrequest
                    ->withHeaders($this->convertHeaders($this->buildHeaders()))
                    ->viaProxy($this->getRandomProxy());
            }
            $subrequests[$name] = $subrequest;
        }
        return $multirequest->send($subrequests);
    }

    private function buildHeaders()
    {
         $params = [
            'X-EBAY-API-COMPATIBILITY-LEVEL' => self::API_COMPATIBILITY_LEVEL,
            'X-EBAY-API-DEV-NAME' => $this->key['developerId'],
            'X-EBAY-API-APP-NAME' => $this->key['appId'],
            'X-EBAY-API-CERT-NAME' => $this->key['certificateId'],
            'X-EBAY-API-CALL-NAME' => $this->params['callname'],
            'X-EBAY-API-SITEID' => $this->params['siteId']
        ];

        return $params;
    }

    private function convertHeaders($headers)
    {
        $result = [];
        foreach ($headers as $k=>$v) {
            $result[] = $k . ': ' . $v;
        }
        return $result;
    }
}