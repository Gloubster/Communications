<?php

namespace Gloubster\Tests\Delivery;

use Gloubster\Delivery\AmazonS3;
use Gloubster\Delivery\Filesystem;
use Gloubster\Delivery\Factory;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getDeliveries
     */
    public function testFromArray($expected, $array)
    {
        $this->assertEquals($expected, Factory::fromArray($array));
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

    public function getDeliveries()
    {
        $amazon = AmazonS3::create('bucket', 'object', array(), 'public', 24);
        $filesystem = Filesystem::create('/path/to/target');

        return array(
            array($amazon, $amazon->toArray()),
            array($filesystem, $filesystem->toArray()),
        );
    }
}
