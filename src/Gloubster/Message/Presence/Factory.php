<?php

namespace Gloubster\Message\Presence;

use Gloubster\Exception\RuntimeException;

class Factory
{
    public static function fromJson($json)
    {
        $data = json_decode($json, true);

        if (null === $data) {
            throw new RuntimeException('Invalid Json data');
        }

        if (!isset($data['type'])) {
            throw new RuntimeException('Invalid Json : Missing `type` property in object');
        }
        
        $classname = sprintf($data['type']);

        $obj = new $classname();

        if (!$obj instanceof PresenceInterface) {
            throw new RuntimeException('Invalid type, PresenceInterface expected');
        }

        foreach ($data as $key => $serializedValue) {
            if (in_array($key, array('name', 'type'))) {
                continue;
            }
            $obj->{'set' . ucfirst($key)}($serializedValue);
        }

        return $obj;
    }
}
