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

use Gloubster\Exception\InvalidArgumentException;
use Gloubster\Exception\LogicException;

interface DeliveryInterface
{

    /**
     * Returns delivery name
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the unique Id to get the data back.
     * This method can not be used before on of the delivery* methods
     *
     * @return string
     *
     * @throws LogicException In case the delivery did not happen yet
     */
    public function getId();

    /**
     * Delivers a content based on a binary data
     *
     * @param string $data Binary data
     *
     * @return mixed A unique id to fetch the data back
     */
    public function deliverBinary($data);

    /**
     * Delivers a content based on a path-file
     *
     * @param string $pathfile the path to the file
     *
     * @return mixed A unique id to fetch the data back
     */
    public function deliverFile($pathfile);

    /**
     * Fetch data given an unique Id
     *
     * @param string $id
     *
     * @return string A binary string of the data
     *
     * @throws InvalidArgumentException In case the id does not exists
     */
    public function fetch($id);

    public function toArray();
    public static function fromArray(array $data);
}
