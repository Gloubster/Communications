<?php

namespace Gloubster\Message;

abstract class AbstractMessage
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
