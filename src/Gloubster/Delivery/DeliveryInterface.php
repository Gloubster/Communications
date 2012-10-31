<?php

/*
 * This file is part of Gloubster.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gloubster\Delivery;

interface DeliveryInterface extends \Serializable
{
    public function deliverBinary($data);
    public function deliverFile($pathfile);
}
