<?php

namespace Gloubster\Receipt;

use Gloubster\Exception\RuntimeException;

class Factory
{
    public static function fromArray(array $data)
    {
        if (!isset($data['name'])) {
            throw new RuntimeException('Invalid receipt data : missing key `name`');
        }

        $name = implode('', array_map(function ($chunk) {
                                  return ucfirst($chunk);
                              }, explode('-', $data['name'])));

        $classname = sprintf('%s\\%sReceipt', __NAMESPACE__, $name);

        if (!class_exists($classname)) {
            throw new RuntimeException(sprintf('Invalid receipt data : class %s does not exists', $classname));
        }

        $obj = new $classname;

        if (!$obj instanceof ReceiptInterface) {
            throw new RuntimeException('Invalid receipt data, ReceiptInterface expected');
        }

        foreach ($data as $key => $serializedValue) {
            if ($key === 'name') {
                continue;
            }
            $obj->{'set' . ucfirst($key)}($serializedValue);
        }

        return $obj;
    }
}
