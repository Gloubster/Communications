<?php

namespace Gloubster\Tests\Delivery;

use Gloubster\Delivery\AmazonS3;
use Gloubster\Delivery\Factory;

/**
 * @covers Gloubster\Delivery\AmazonS3
 */
class AmazonS3Test extends AbstractDeliveryTest
{
    protected $target;
    private $client;

    public function getDelivery()
    {
        $delivery = AmazonS3::create('sweet-kittens', 'object-name', array(), 'public-read', 12);
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

    /**
     * @expectedException Gloubster\Exception\RuntimeException
     */
    public function testDeliverBinaryFailWithAws()
    {
        $exception = $this->getMockBuilder('Aws\Common\Exception\BadMethodCallException')
            ->disableOriginalConstructor()
            ->getMock();

        $this->client->expects($this->once())
            ->method('getCommand')
            ->will($this->returnCallback(function ($command, $values) use ($exception) {
               throw $exception;
            }));

        $data = 'random' . mt_rand();
        $id = $this->getDelivery()->deliverBinary($data);
    }

    /**
     * @expectedException Gloubster\Exception\RuntimeException
     */
    public function testDeliverBinaryFailWithAnyException()
    {
        $exception = new \Exception();

        $this->client->expects($this->once())
            ->method('getCommand')
            ->will($this->returnCallback(function ($command, $values) use ($exception) {
               throw $exception;
            }));

        $data = 'random' . mt_rand();
        $id = $this->getDelivery()->deliverBinary($data);
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

    /**
     * @expectedException Gloubster\Exception\RuntimeException
     */
    public function testFetchWithAwsException()
    {
        $exception = $this->getMockBuilder('Aws\Common\Exception\BadMethodCallException')
            ->disableOriginalConstructor()
            ->getMock();

        $this->client->expects($this->at(0))
            ->method('getCommand')
            ->will($this->returnCallback(function () use ($exception) {
                throw $exception;
            }));

        $delivery = $this->getDelivery();
        $delivery->fetch('boom');
    }

    /**
     * @expectedException Gloubster\Exception\RuntimeException
     */
    public function testFetchWithAnyException()
    {
        $exception = new \Exception('bim');

        $this->client->expects($this->at(0))
            ->method('getCommand')
            ->will($this->returnCallback(function () use ($exception) {
                throw $exception;
            }));

        $delivery = $this->getDelivery();
        $delivery->fetch('boom');
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

    public function testGetSetMultipartUploadChunk()
    {
        $chunk = 476;
        $delivery = $this->getDelivery();
        $delivery->setMultipartUploadChunk($chunk);
        $this->assertEquals($chunk, $delivery->getMultipartUploadChunk());
    }

    public function testGetSetAcl()
    {
        $acl = 'private-read';
        $delivery = $this->getDelivery();
        $delivery->setAcl($acl);
        $this->assertEquals($acl, $delivery->getAcl());
    }

    public function testGetSetBucketName()
    {
        $bucketName = 'polly-bucket';
        $delivery = $this->getDelivery();
        $delivery->setBucketName($bucketName);
        $this->assertEquals($bucketName, $delivery->getBucketName());
    }

    public function testGetSetObjectKey()
    {
        $objectKey = 'polly-name';
        $delivery = $this->getDelivery();
        $delivery->setObjectKey($objectKey);
        $this->assertEquals($objectKey, $delivery->getObjectKey());
    }

    public function testGetSetDelivered()
    {
        $delivered = true;
        $delivery = $this->getDelivery();
        $delivery->setDelivered($delivered);
        $this->assertEquals($delivered, $delivery->getDelivered());
    }

    public function testGetSetOptions()
    {
        $options = array('param1'=>'value1', 'param2'=>'value2');
        $delivery = $this->getDelivery();
        $delivery->setOptions($options);
        $this->assertEquals($options, $delivery->getOptions());
    }

    public function testGetSetClient()
    {
        $client = $this->getMockBuilder('Aws\\S3\\S3Client')
            ->disableOriginalConstructor()
            ->getMock();
        $delivery = $this->getDelivery();
        $delivery->setClient($client);
        $this->assertEquals($client, $delivery->getClient());

        $client = null;
        $delivery->setClient($client);
        $this->assertNull($delivery->getClient());
    }

    public function testToArray()
    {
        $delivery = $this->getDelivery();
        $delivery->setClient(null);
        $this->assertEquals($delivery, Factory::fromArray($delivery->toArray()));
    }

    public function testAmazonFromArray()
    {
        $delivery = $this->getDelivery();
        $delivery->setClient(null);
        $this->assertEquals($delivery, AmazonS3::fromArray($delivery->toArray()));
    }
}
