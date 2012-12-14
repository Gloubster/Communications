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
