<?php

namespace Gloubster\Message;

interface MessageInterface
{
    /**
     * Returns a readonly identifier of the message
     *
     * @return string
     */
    public function getName();

    /**
     * Returns a serialized representation of the message
     *
     * @return string
     */
    public function toJson();

    /**
     * Creates a new message instance based on serialized data
     *
     * @param type $json
     *
     * @return MessageInterface
     */
    public static function fromJson($json);
}
