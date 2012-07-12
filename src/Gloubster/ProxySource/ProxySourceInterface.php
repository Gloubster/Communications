<?php

namespace Gloubster\ProxySource;

interface ProxySourceInterface
{
    public function getDatas($ressource);
    public function setExpiration($expiration);
    public function getExpiration();

    /**
     * Build the delivery giving the configuration
     *
     * @param array $configuration
     *
     * @throws Gloubster\Exception\RuntimeException On failure
     */
    public static function build(array $configuration);
}

