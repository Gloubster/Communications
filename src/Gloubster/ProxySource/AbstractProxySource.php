<?php

namespace Gloubster\ProxySource;

abstract class AbstractProxySource implements ProxySourceInterface
{
    protected $expiration = 300;

    public function getDatas($ressource)
    {
        if ( ! $this->has($this->getHash($ressource))) {
            return $this->store($this->getHash($ressource), $this->fetch($ressource), $this->expiration);
        }

        return $this->get($this->getHash($ressource));
    }

    public function setExpiration($expiration)
    {
        $this->expiration = (int) $expiration;
    }

    public function getExpiration()
    {
        return $this->expiration;
    }

    protected function fetch($ressource)
    {
        return @file_get_contents($ressource);
    }

    abstract protected function has($key);

    abstract protected function get($key);

    abstract protected function store($key, $datas, $expiration);

    private function getHash($ressource)
    {
        return 'proxy-source' . md5($ressource);
    }
}

