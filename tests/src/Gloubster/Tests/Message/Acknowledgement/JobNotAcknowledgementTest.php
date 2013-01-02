<?php

namespace Gloubster\Tests\Message\Acknowledgement;

use Gloubster\Message\Acknowledgement\JobNotAcknowledgement;
use Gloubster\Message\Factory as MessageFactory;

class JobNotAcknowledgementTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function itMustBeSerializable()
    {
        $ack = new JobNotAcknowledgement();
        $date = new \DateTime('+5days');
        $ack->setCreatedOn($date);
        $ack->setReason('Man machine');

        $data = json_decode($ack->toJson(), true);
        $this->assertEquals($date->format(DATE_ATOM), $data['createdOn']);
        $this->assertEquals('Man machine', $data['reason']);

        $unserialized = MessageFactory::fromJson($ack->toJson());
        $this->assertEquals($ack->getCreatedOn()->format(DATE_ATOM), $unserialized->getCreatedOn()->format(DATE_ATOM));
    }
}
