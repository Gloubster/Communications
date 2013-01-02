<?php

namespace Gloubster\Tests\Message\Acknowledgement;

use Gloubster\Message\Acknowledgement\JobAcknowledgement;

class JobAcknowledgementTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function itMustBeSerializable()
    {
        $ack = new JobAcknowledgement();
        $date = new \DateTime('+5days');
        $ack->setCreatedOn($date);

        $data = json_decode($ack->toJson(), true);
        $this->assertEquals($date->format(DATE_ATOM), $data['createdOn']);
    }
}
