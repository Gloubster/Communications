<?php

/*
 * This file is part of Gloubster.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gloubster\Communication;

use Gloubster\Configuration;
use Gloubster\Delivery\Factory;
use Gloubster\Exception\RuntimeException;

class Query implements \Serializable
{
    protected $uuid;
    protected $file;
    protected $parameters;
    protected $delivery;
    protected $deliveryName;
    protected $deliverySignature;

    const FUNCTION_TRANSMUTE_IMAGE = 'transmute_image';

    public function __construct($uuid, $file, $deliveryName, $deliverySignature, array $parameters = array())
    {
        $this->uuid = $uuid;
        $this->file = $file;
        $this->deliveryName = $deliveryName;
        $this->deliverySignature = $deliverySignature;
        $this->parameters = $parameters;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function getDelivery(Factory $factory, Configuration $configuration)
    {
        if ( ! $this->delivery) {

            $this->delivery = $factory->build($configuration);

            if ($this->delivery->getSignature() !== $this->deliverySignature) {
                throw new RuntimeException('Wrong delivery configuration');
            }
        }

        return $this->delivery;
    }

    public function serialize()
    {
        return json_encode(array(
                'uuid'              => $this->uuid,
                'file'              => $this->file,
                'deliveryName'      => $this->deliveryName,
                'deliverySignature' => $this->deliverySignature,
                'parameters'        => $this->parameters,
            ));
    }

    public function unserialize($serializedDatas)
    {
        $datas = json_decode($serializedDatas, true);

        if (null === $datas) {
            throw new RuntimeException('Data corrupted');
        }

        $this->uuid = $datas['uuid'];
        $this->file = $datas['file'];
        $this->deliveryName = $datas['deliveryName'];
        $this->deliverySignature = $datas['deliverySignature'];
        $this->parameters = $datas['parameters'];
    }
}
