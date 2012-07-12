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

    /**
     * {@inheritdoc}
     */
    public static function build(array $configuration)
    {
        if (false === isset($configuration['host']) || false === isset($configuration['port'])) {
            throw new InvalidArgumentException('Configuration must contain host and port keys');
        }

        $redis = new \Redis();
        $redis->pconnect($configuration['host'], $configuration['port']);

        try {

            if ('+PONG' !== $redis->ping()) {
                throw new RuntimeException('Unable to connect to redis server');
            }
        } catch (\RedisException $e) {
            throw new RuntimeException('Something wrong happened with '
                . 'Redis Server, check connection and sever load');
        }

        return new static($redis);
    }
}

