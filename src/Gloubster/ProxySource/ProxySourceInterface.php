<?php

namespace Gloubster\ProxySource;

interface ProxySourceInterface
{
    public function getDatas($ressource);
    public function setExpiration($expiration);
    public function getExpiration();
}

