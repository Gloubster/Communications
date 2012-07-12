<?php

namespace Gloubster\ProxySource;

class Redis extends AbstractProxySource
{
    protected $redis;

    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
        $this->redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_NONE);
    }

    protected function has($key)
    {
        return $this->redis->exists($key);
    }

    protected function get($key)
    {
        return $this->redis->get($key);
    }

    protected function store($key, $datas, $expiration)
    {
        if ($datas) {
            $this->redis->setex($key, $expiration, $datas);
        }

        return $datas;
    }
}

