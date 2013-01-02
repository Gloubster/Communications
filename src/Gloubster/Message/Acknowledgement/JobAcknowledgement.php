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

class JobAcknowledgement extends AbstractMessage implements AcknowledgementInterface
{
    private $createdOn;

    public function setCreatedOn(\DateTime $date = null)
    {
        $this->createdOn = $date;

        return $this;
    }

    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    public function getName()
    {
        return 'acknowledgement';
    }

    protected function getArrayData()
    {
        return array(
            'name'      => $this->getName(),
            'type'      => get_class($this),
            'createdOn' => $this->createdOn ? $this->createdOn->format(DATE_ATOM) : null,
        );
    }
}
