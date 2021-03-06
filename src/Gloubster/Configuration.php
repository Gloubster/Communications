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

use JsonSchema\Validator;
use Gloubster\Exception\RuntimeException;

/**
 * Gloubster configuration for Workers and Client
 *
 * This configuration loads a configuration file which have to be compliant
 * with the schema.
 *
 * The configuration object provides an ArrayAccess interface.
 */
class Configuration implements \ArrayAccess
{
    const EXCHANGE_DISPATCHER = 'phrasea.subdef.dispatcher';
    const EXCHANGE_MONITOR = 'phrasea.monitor';

    const QUEUE_ERRORS = 'phrasea.subdefs.errors';
    const QUEUE_LOGS = 'phrasea.subdefs.logs';
    const QUEUE_IMAGE_PROCESSING = 'phrasea.subdefs.image';
    const QUEUE_VIDEO_PROCESSING = 'phrasea.subdefs.video';

    const ROUTINGKEY_ERROR = 'phrasea.error';
    const ROUTINGKEY_LOG = 'phrasea.log';
    const ROUTINGKEY_IMAGE_PROCESSING = 'phrasea.subdef.image';
    const ROUTINGKEY_VIDEO_PROCESSING = 'phrasea.subdef.video';
    
    protected $schemas = array();
    protected $validator;
    protected $configuration;

    /**
     * Constructor
     *
     * @param string $json The configuration to be loaded
     * @param array $extra_schemas An array of extra schema for json validation
     *
     * @throws RuntimeException In case the provided Json does not match schema(s)
     */
    public function __construct($json, array $extra_schemas = array())
    {
        $schemaFile = __DIR__ . '/../../resources/configuration.schema.json';
        $schemas = array_merge(array(file_get_contents($schemaFile)), $extra_schemas);

        foreach ($schemas as $schema) {
            $jsonSchema = json_decode($schema);

            if (!$jsonSchema) {
                throw new RuntimeException('Invalid configuration schema');
            }

            $this->schemas[] = $jsonSchema;
        }

        $this->validator = new Validator();
        $errors = array();
        $configuration = json_decode($json);

        foreach ($this->schemas as $schema) {
            $this->validator->check($configuration, $schema);

            if (!$this->validator->isValid()) {
                foreach ($this->validator->getErrors() as $error) {
                    $errors[] = sprintf("[%s] %s\n", $error['property'], $error['message']);
                }
            }
        }

        if ($errors) {
            throw new RuntimeException(
                sprintf('Invalid configuration ' . ': %s', implode(', ', $errors))
            );
        }

        $this->configuration = json_decode($json, true);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->configuration[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->configuration[$offset];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->configuration[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->configuration[$offset]);
    }
}
