<?php

/*
 * This file is part of Gloubster.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gloubster\Delivery;

use Gloubster\Delivery\Exception\ItemDoesNotExistsException;
use Gloubster\Exception\RuntimeException;
use Gloubster\Exception\InvalidArgumentException;
use Gloubster\Communication\Result;

/**
 * Redis store delivery system
 */
class RedisStore implements DeliveryInterface
{
    protected $redis;
    protected $signature;

    /**
     * Constructor
     *
     * @param \Redis $redis
     * @param string $signature
     */
    public function __construct(\Redis $redis, $signature)
    {
        $this->redis = $redis;
        $this->signature = $signature;

        if (false === $this->redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_IGBINARY)) {
            $this->redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);
        }
        $this->redis->setOption(\Redis::OPT_PREFIX, 'gloubster:');
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        try {
            $this->redis->close();
        } catch (\RedisException $e) {

        }
        unset($this->redis);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'RedisStore';
    }

    /**
     * {@inheritdoc}
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * {@inheritdoc}
     */
    public function deliver($key, Result $result, $binaryData)
    {
        try {
            $datas = array('result-' . $key => $result, 'binary-' . $key => $binaryData);

            if (false === $this->redis->mset($datas)) {
                throw new RuntimeException('Unable to deliver the result');
            }

            foreach (array_keys($datas) as $redisKey) {
                $this->redis->setTimeout($redisKey, 3600 * 7);
            }
        } catch (\RedisException $e) {
            throw new RuntimeException('Something wrong happened with '
                . 'Redis Server, check connection and sever load');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function retrieve($key)
    {
        try {
            if (false === $ret = $this->redis->get('result-' . $key)) {
                throw new ItemDoesNotExistsException(sprintf('Item %s does not exists', 'result-' . $key));
            }

            if ( ! $ret instanceof Result) {
                throw new RuntimeException('Data were corrupted');
            }

            return $ret;
        } catch (\RedisException $e) {
            throw new RuntimeException('Something wrong happened with '
                . 'Redis Server, check connection and sever load');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveData($key)
    {
        try {
            if (false === $ret = $this->redis->get('binary-' . $key)) {
                throw new ItemDoesNotExistsException(sprintf('Item %s does not exists', 'binary-' . $key));
            }

            return $ret;
        } catch (\RedisException $e) {
            throw new RuntimeException('Something wrong happened with '
                . 'Redis Server, check connection and sever load');
        }
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

        return new static($redis, md5(json_encode(array($configuration['host'], (int) $configuration['port']))));
    }
}
