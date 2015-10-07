<?php
namespace Catalog\EbayAPI;

use Catalog\Exception\EmptyParam;

class EbayOptions extends CurlOptions
{
    private $token;

    private  $callName;

    private  $developerID;

    private $applicationID;

    private $certificateID;

    private $siteCode = 1;

    private $callNameForHeader;

    private $siteIdList = [
        'AU' => 15,
        'AT' => 16,
        'BENL' => 123,
        'BEFR' => 23,
        'CA' => 2,
        'CAFR' => 210,
        'CN' => 223,
        'MOTORS' => 100,
        'FR' => 71,
        'DE' => 77,
        'HK' => 201,
        'IN' => 203,
        'IE' => 205,
        'IT' => 101,
        'MY' => 207,
        'NL' => 146,
        'PH' => 211,
        'PL' => 212,
        'SG' => 216,
        'ES' => 186,
        'SE' => 218,
        'CH' => 193,
        'TW' => 196,
        'UK' => 3,
        'US' => 0,
    ];


    public function setApplicationID($applicationID)
    {
        $this->applicationID = $applicationID;
        return $this;
    }

    public function setCallName($callName)
    {
        $this->callName = $callName;
        return $this;
    }

    public function isPost()
    {
        $this->isPost = true;
        return $this;
    }

    public function setCertificateID($certificateID)
    {
        $this->certificateID = $certificateID;
        return $this;
    }

    public function setCallNameForHeader($callNameForHeader)
    {
        $this->callNameForHeader = $callNameForHeader;
        return $this;
    }


    public function setDeveloperID($developerID)
    {
        $this->developerID = $developerID;
        return $this;
    }

    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function setSiteCode($siteCode)
    {
        $this->siteCode = $siteCode;
        return $this;
    }

    private function buildRequestCredentials()
    {
        $request = '<RequesterCredentials>';
        $request .= '<eBayAuthToken>'.$this->token.'</eBayAuthToken>';
        $request .= '</RequesterCredentials>';

        return $request;
    }

    private function buildHeaders()
    {
        $this->headers = array(
            'X-EBAY-API-COMPATIBILITY-LEVEL: 863',
            'X-EBAY-API-DEV-NAME: ' . $this->developerID,
            'X-EBAY-API-APP-NAME: ' . $this->applicationID,
            'X-EBAY-API-CERT-NAME: ' . $this->certificateID,
            'X-EBAY-API-CALL-NAME: ' . $this->callNameForHeader,
            'X-EBAY-API-SITEID: '.$this->siteCode,
        );
    }

    public function getResponse($postData = null)
    {
        $query = '<?xml version="1.0" encoding="utf-8"?>
        <' . $this->callName . ' xmlns="urn:ebay:apis:eBLBaseComponents">' . $this->buildRequestCredentials();
        $query .= $postData;
        $query .= '</' . $this->callName . '>';
        $this->buildHeaders();
        $ch = curl_init();
        curl_setopt_array($ch, $this->getOptionForCurl($query));
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function getCountryCodeByShortName($shortName)
    {
        if (!array_key_exists($shortName, $this->siteIdList)) {
            throw new EmptyParam('Not founded site id ' . $shortName);
        }
        return $this->siteIdList[$shortName];
    }






}