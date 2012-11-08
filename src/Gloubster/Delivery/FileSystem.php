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

use Gloubster\Exception\RuntimeException;

class FileSystem implements DeliveryInterface
{
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
        return $this->target;
    }

    /**
     * {@inheritdoc}
     */
    public function deliverBinary($data)
    {
        file_put_contents($this->target, $data);

        return $this->target;
    }

    /**
     * {@inheritdoc}
     */
    public function deliverFile($pathfile)
    {
        copy($pathfile, $this->target);

        return $this->target;
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
