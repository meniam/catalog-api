<?php

namespace Catalog\Taobao;

class Config
{
    private static $keys = [];
    private static $proxies = [];

    public static function setKeys(array $keys)
    {
        self::$keys = $keys;
    }

    public static function setProxies(array $proxies)
    {
        self::$proxies = $proxies;
    }

    public static function getKey()
    {
        return self::$keys[array_rand(self::$keys, 1)];
    }

    public static function getProxy()
    {
        return self::$proxies[array_rand(self::$proxies, 1)];
    }
}