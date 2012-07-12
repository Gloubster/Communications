<?php

namespace Gloubster\ProxySource;

class NullProxy extends AbstractProxySource
{

    protected function has($key)
    {
        return false;
    }

    protected function get($key)
    {
        return null;
    }

    protected function store($key, $datas, $expiration)
    {
        return $datas;
    }
    
    public static function build(array $configuration)
    {
        return new static;
    }
}

