<?php

/*
 * This file is part of Gloubster.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gloubster\Message;

abstract class AbstractMessage implements MessageInterface
{
    /**
     * {@inheritdoc}
     */
    public function toJson()
    {
        return json_encode(array_merge(array(
            'name' => $this->getName(),
            'type' => get_class($this),
        ), $this->getArrayData()));
    }

    /**
     * {@inheritdoc}
     */
    public static function fromJson($json)
    {
        return Factory::fromJson($json);
    }

    /**
     * An array serialization of the class.
     *
     * @return Array
     */
    protected abstract function getArrayData();
}
