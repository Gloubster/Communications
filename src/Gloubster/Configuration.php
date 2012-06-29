<?php

/*
 * This file is part of Gloubster.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gloubster;

class Configuration implements \ArrayAccess
{
    protected $schema;
    protected $validator;
    protected $configuration;

    public function __construct($file)
    {
        $configuration = json_decode(file_get_contents($file));

        $schemaFile = __DIR__ . '/../../ressources/configuration.schema.json';

        $this->schema = json_decode(file_get_contents($schemaFile));

        if(!$this->schema) {
            throw new Exception\RuntimeException('Invalid configuration schema');
        }

        $this->validator = new \JsonSchema\Validator();
        $this->validator->check($configuration, $this->schema);

        if ( ! $this->validator->isValid()) {
            throw new Exception\RuntimeException('Invalid configuration');
        }

        $this->configuration = json_decode(file_get_contents($file), true);
    }

    public function offsetExists($offset)
    {
        return isset($this->configuration[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->configuration[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->configuration[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->configuration[$offset]);
    }
}
