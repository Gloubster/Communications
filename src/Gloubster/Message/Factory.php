<?php

namespace Gloubster\Message;

class Factory
{
    public static function fromJson($json)
    {
        $data = json_decode($json, true);

        $factory = implode('\\', array_slice(explode('\\', $data['type']), 0, -1)) . '\\Factory';

        return $factory::fromJson($json);
    }
}
