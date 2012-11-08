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
     */
    public function getId();

    /**
     * Delivers a content based on a binary data
     *
     * @param string $data Binary data
     * @return mixed A unique id to fetch the data back
     */
    public function deliverBinary($data);

    /**
     * Delivers a content based on a path-file
     *
     * @param string $pathfile the path to the file
     * @return mixed A unique id to fetch the data back
     */
    public function deliverFile($pathfile);
}
