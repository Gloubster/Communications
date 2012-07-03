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
 * Filesystem store delivery system
 */
class FilesystemStore implements DeliveryInterface
{
    protected $signature;
    protected $path;

    /**
     * Constructor
     *
     * @param \Redis $redis
     * @param string $signature
     */
    public function __construct($path, $signature)
    {
        $this->path = rtrim($path, DIRECTORY_SEPARATOR);
        $this->signature = $signature;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'FilesystemStore';
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
    public function deliver($key, Result $result)
    {
        if (false === file_put_contents($this->getFile($key, true), serialize($result))) {
            throw new RuntimeException('Unable to deliver the result');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function retrieve($key)
    {
        if (false === $ret = @file_get_contents($this->getFile($key))) {
            throw new ItemDoesNotExistsException(sprintf('Item %s does not exists', $key));
        }

        $unserialized = @unserialize($ret);

        if ( ! $unserialized instanceof Result) {
            throw new RuntimeException('Data were corrupted');
        }

        return $unserialized;
    }

    protected function getFile($key, $shouldNotExist = false)
    {
        $pathfile = $this->path;

        $length = strlen($key);

        for ($i = 0; $i < $length; $i ++ ) {
            if ($i % 3 === 0) {
                $pathfile .= DIRECTORY_SEPARATOR;
            }
            $pathfile .= $i;
        }

        if (true === $shouldNotExist && file_exists($pathfile)) {
            throw new RuntimeException(sprintf('Unable to get unique file `%s`', $pathfile));
        }

        if (false === file_exists(dirname($pathfile)) && false === @mkdir(dirname($pathfile), 0777, true)) {
            throw new RuntimeException(sprintf('Unable to create destination directory `%s`', basename($pathfile)));
        }

        return $pathfile;
    }

    /**
     * {@inheritdoc}
     */
    public static function build(array $configuration)
    {
        if (false === isset($configuration['path'])) {
            throw new InvalidArgumentException('Configuration must contain a path key');
        }

        if (false === file_exists($configuration['path']) && false === @mkdir($configuration['path'], 0777, true)) {
            throw new InvalidArgumentException(sprintf('Path `%s` is not available', $configuration['path']));
        }

        if (false === is_writable($configuration['path'])) {
            throw new InvalidArgumentException(sprintf('Path `%s` is not writable'));
        }

        return new static($configuration['path'], md5(json_encode(array(filectime($configuration['path'])))));
    }
}
