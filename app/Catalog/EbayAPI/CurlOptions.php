<?php
namespace Catalog\EbayAPI;

class CurlOptions
{
    protected $url;

    protected $isPost = false;

    protected $headers;

    private static $proxy;

    protected $addressPool = array(
        /*'209.104.194.135:3128',
        '206.71.50.36:3128',
        '66.109.24.62:3128',
        '209.104.194.140:3128',
        '66.109.24.52:3128',
        '209.104.194.132:3128',
        '209.104.194.134:3128',
        '66.109.24.55:3128',
        '206.71.50.37:3128',
        '66.109.24.58:3128',
        '66.109.24.60:3128',
        '66.109.24.61:3128',
        '209.104.194.137:3128',
        '209.104.194.139:3128',
        '66.109.24.59:3128',
        '206.71.50.39:3128',
        '206.71.50.43:3128',
        '206.71.50.45:3128',
        '206.71.50.46:3128',
        '206.71.50.41:3128',
        '209.104.194.138:3128',
        '206.71.50.44:3128',
        '206.71.50.40:3128',
        '209.104.194.136:3128'*/
        '107.170.62.112:3128'
    );


    protected function getOptionForCurl($query)
    {
        self::$proxy = $this->addressPool[array_rand($this->addressPool)];
        $optionCurl = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $this->url,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_PROXY => self::$proxy
        );
        if ($this->isPost) {
            $optionCurl[CURLOPT_HTTPHEADER] = $this->headers;
            $optionCurl[CURLOPT_POST] = true;
            $optionCurl[CURLOPT_POSTFIELDS] = $query;
            $optionCurl[CURLOPT_SSL_VERIFYPEER] = false;
            $optionCurl[CURLOPT_SSL_VERIFYHOST] = false;
        }
        return $optionCurl;

    }

    public static function getProxy()
    {
        return self::$proxy;
    }
}