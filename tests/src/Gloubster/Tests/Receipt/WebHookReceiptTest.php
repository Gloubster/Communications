<?php

namespace Gloubster\Tests\Receipt;

use Gloubster\Receipt\WebHookReceipt;

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

        $this->hook = new WebHookReceipt();
        $this->hook->setUrl($this->url)
            ->setParameter($this->parameter)
            ->setUseBody($this->body);
        $this->hook->setClient($this->client);
    }

    public function testToArrayFromArray()
    {
        $hook = new WebHookReceipt($this->url, $this->parameter, $this->body);
        $this->assertEquals($hook, WebHookReceipt::fromArray($hook->toArray()));
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

        $job = $this->getMockBuilder('Gloubster\Message\Job\JobInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $job->expects($this->any())
            ->method('toJson')
            ->will($this->returnValue($serializedData));

        $this->hook->acknowledge($job);
    }
}
