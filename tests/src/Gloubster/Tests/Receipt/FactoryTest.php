<?php

namespace Gloubster\Tests\Receipt;

use Gloubster\Receipt\Factory;
use Gloubster\Receipt\WebHookReceipt;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFromArray()
    {
        $webhook = WebHookReceipt::create('htpp://www.example.com/webhook', 'grosse-charge');

        $this->assertEquals($webhook, Factory::fromArray($webhook->toArray()));
    }

    /**
     * @expectedException Gloubster\Exception\RuntimeException
     */
    public function testFromArrayFailsWithWrongClassname()
    {
        Factory::fromArray(array('name' => 'Gloubosor'));
    }

    /**
     * @expectedException Gloubster\Exception\RuntimeException
     */
    public function testFromArrayFailsWithoutgClassname()
    {
        Factory::fromArray(array());
    }
}
