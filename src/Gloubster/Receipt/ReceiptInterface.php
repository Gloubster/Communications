<?php

/*
 * This file is part of Gloubster.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gloubster\Receipt;

use Gloubster\Job\JobInterface;

interface ReceiptInterface extends \Serializable
{
    public function acknowledge(JobInterface $job);
}
