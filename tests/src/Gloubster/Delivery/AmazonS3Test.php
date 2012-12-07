<?php

namespace Gloubster\Delivery;

require_once __DIR__ . '/AbstractDeliveryTest.php';

/**
 * @covers Gloubster\Delivery\AmazonS3
 */
class AmazonS3Test extends AbstractDeliveryTest
{
    protected $target;
    private $client;

    public function getDelivery()
    {
        $delivery = new AmazonS3('sweet-kittens', 'object-name', array(), 'public-read', 12);
        $delivery->setClient($this->client);

        return $delivery;
    }

    public function setUp()
    {
        $this->currentObject = null;

        $client = $this->getMockBuilder('Aws\S3\S3Client')
            ->disableOriginalConstructor()
            ->getMock();

        $this->client = $client;
    }

    protected function tearDown()
    {
    }

    public function readTarget()
    {
        return $this->currentObject;
    }

    public $currentObject;

    public function testDeliverBinary()
    {
        $that = $this;

        $this->client->expects($this->once())
            ->method('getCommand')
            ->will($this->returnCallback(function ($command, $values) use (&$that) {
               $that->currentObject = $values['Body'];
            }));

        parent::testDeliverBinary();
    }


    public function testFetch()
    {
        $data = null;

        $this->client->expects($this->at(0))
            ->method('getCommand')
            ->will($this->returnCallback(function ($command, $values) use (&$data) {
               $data = $values['Body'];
            }));

        $command = $this->getMockBuilder('Guzzle\Service\Command\CommandInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $command->expects($this->once())
            ->method('execute')
            ->will($this->returnValue(array('Body' => &$data)));

        $this->client->expects($this->at(1))
            ->method('getCommand')
            ->will($this->returnValue($command));

        parent::testFetch();
    }

    public function testGetIdAfterDeliverFile()
    {
        $this->markTestSkipped('Need to test the multipart upload');
        parent::testGetIdAfterDeliverFile();
    }

    public function testDeliverFile()
    {
        $this->markTestSkipped('Need to test the multipart upload');
        parent::testDeliverFile();
    }

    public function testSerialize()
    {
        $delivery = new AmazonS3('duckduck', 'dest-key', array('token' => "bimboum", 'private', 24));
        $this->assertEquals($delivery, unserialize(serialize($delivery)));
    }
}
