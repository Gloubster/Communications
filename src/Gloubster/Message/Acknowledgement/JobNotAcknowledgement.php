<?php

/*
 * This file is part of Gloubster.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gloubster\Message\Acknowledgement;

use Gloubster\Message\AbstractMessage;

class JobNotAcknowledgement extends AbstractMessage implements AcknowledgementInterface
{
    private $createdOn;
    private $reason;

    public function setCreatedOn(\DateTime $date = null)
    {
        $this->createdOn = $date;

        return $this;
    }

    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    public function setReason($reason)
    {
        $this->reason = $reason;

        return $this;
    }

    public function getReason()
    {
        return $this->reason;
    }

    public function getName()
    {
        return 'not-acknowledgement';
    }

    protected function getArrayData()
    {
        return array(
            'name'      => $this->getName(),
            'type'      => get_class($this),
            'reason'    => $this->reason,
            'createdOn' => $this->createdOn ? $this->createdOn->format(DATE_ATOM) : null,
        );
    }
}
