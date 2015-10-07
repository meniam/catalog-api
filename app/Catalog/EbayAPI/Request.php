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
        return array(
            'X-EBAY-API-COMPATIBILITY-LEVEL' => self::API_COMPATIBILITY_LEVEL,
            'X-EBAY-API-DEV-NAME' => $this->key['developerId'],
            'X-EBAY-API-APP-NAME' => $this->key['appId'],
            'X-EBAY-API-CERT-NAME' => $this->key['certificateId'],
            'X-EBAY-API-CALL-NAME' => $this->params['callname'],
            'X-EBAY-API-SITEID' => $this->params['siteId']
        );
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