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

use Gloubster\Exception\InvalidArgumentException;
use Gloubster\Exception\LogicException;

class Filesystem implements DeliveryInterface
{
    private $delivered = false;
    private $target;

    public static function create($target)
    {
        $obj = new static();
        $obj->setTarget($target);

        return $obj;
    }

    public function setTarget($target)
    {
        $this->target = $target;
    }

    public function getTarget()
    {
        return $this->target;
    }

    public function setDelivered($delivered)
    {
        $this->delivered = $delivered;
    }

    public function getDelivered()
    {
        return $this->delivered;
    }



    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'filesystem';
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        if (! $this->delivered) {
            throw new LogicException('Data has not been delivered yet');
        }

        return $this->target;
    }

    /**
     * {@inheritdoc}
     */
    public function deliverBinary($data)
    {
        file_put_contents($this->target, $data);
        $this->delivered = true;

        return $this->target;
    }

    /**
     * {@inheritdoc}
     */
    public function deliverFile($pathfile)
    {
        copy($pathfile, $this->target);
        $this->delivered = true;

        return $this->target;
    }

    /**
     * {@inheritdoc}
     */
    public function fetch($id)
    {
        if (! file_exists($id)) {
            throw new InvalidArgumentException(sprintf('Item %s does not exist', $id));
        }

        return file_get_contents($id);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        $data = array('name' => $this->getName());

        foreach ($this as $key => $value) {
            $data[$key] = $value;
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public static function fromArray(array $data)
    {
        return Factory::fromArray($data);
    }
}
