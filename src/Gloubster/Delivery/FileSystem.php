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
use Gloubster\Exception\RuntimeException;

class FileSystem implements DeliveryInterface
{
    private $delivered = false;
    private $target;

    public function __construct($target)
    {
        $this->target = $target;
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
        if (!$this->delivered) {
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
        if (!file_exists($id)) {
            throw new InvalidArgumentException(sprintf('Item %s does not exist', $id));
        }

        return file_get_contents($id);
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return json_encode(array('target' => $this->target));
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        $data = json_decode($serialized, true);

        if (!$data) {
            throw new RuntimeException('Unable to unserialize data');
        }

        $this->target = $data['target'];

        return $this;
    }
}
