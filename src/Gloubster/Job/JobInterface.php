<?php

/*
 * This file is part of Gloubster.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gloubster\Job;

interface JobInterface extends \Serializable
{
    public function isOk($throwException = false);

    public function setError($boolean);

    public function setParameters(array $parameters);

    public function getParameters();

    public function getMandatoryParameters();

    public function isOnError();

    public function requireReceipt();

    public function setWorkerId($id);

    public function getWorkerId();

    public function getDelivery();

    public function getBeginning();

    public function getEnd();

    public function setEnd(\DateTime $date);

    public function setProcessDuration($duration);

    public function getProcessDuration();

    public function setDeliveryDuration($duration);

    public function getDeliveryDuration();

    public function getRoutingKey();

    public function getExchangeName();
}

