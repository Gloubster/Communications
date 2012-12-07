<?php

namespace Gloubster\Receipt;

class WebHookReceiptTest extends \PHPUnit_Framework_TestCase
{
    private $hook;
    private $url;
    private $parameter;
    private $body;
    private $client;

    public function setUp()
    {
        parent::setUp();

        $this->url = 'http://www.example.com';
        $this->parameter = 'pipou';
        $this->body = false;

        $this->client = $this->getMockBuilder('Guzzle\Http\Client')
            ->disableOriginalConstructor()
            ->getMock();

        $this->hook = new WebHookReceipt($this->url, $this->parameter, $this->body);
        $this->hook->setClient($this->client);
    }

    public function testSerialize()
    {
        $hook = new WebHookReceipt($this->url, $this->parameter, $this->body);
        $this->assertEquals($hook, unserialize(serialize($hook)));
    }

    public function testAcknowledge()
    {
        $serializedData = json_encode(array('result' => 'BINGO'));

        $request = $this->getMockBuilder('Guzzle\Http\Message\EntityEnclosingRequestInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $request->expects($this->once())
            ->method('setPostField')
            ->with($this->equalTo($this->parameter), $this->equalTo($serializedData))
            ;

        $this->client->expects($this->once())
            ->method('post')
            ->with($this->equalTo($this->url), $this->equalTo(array('Content-Type' => 'application/json')), $this->equalTo(null))
            ->will($this->returnValue($request));

        $job = $this->getMockBuilder('Gloubster\Job\JobInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $job->expects($this->any())
            ->method('serialize')
            ->will($this->returnValue($serializedData));

        $this->hook->acknowledge($job);
    }
}
