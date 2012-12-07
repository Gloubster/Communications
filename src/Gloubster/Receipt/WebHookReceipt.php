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
use Guzzle\Http\Client;
use Guzzle\Common\Exception\GuzzleException;
use Gloubster\Exception\RuntimeException;

class WebHookReceipt implements ReceiptInterface
{
    private $url;
    private $parameter;
    private $useBody;
    /**
     * @var \Guzzle\Http\Client
     */
    private $client;

    public function __construct($url, $parameter = 'payload', $useBody = false)
    {
        $this->url = $url;
        $this->parameter = $parameter;
        $this->useBody = $useBody;
        $this->initClient();
    }

    /**
     * {@inheritdoc}
     */
    public function acknowledge(JobInterface $job)
    {
        $data = $job->serialize();
        try {
            $request = $this->client->post($this->url, array('Content-Type' => 'application/json'), $this->useBody ? $data : null);

            if ($this->parameter) {
                $request->setPostField($this->parameter, $data);
            }
            $request->send();
        } catch (GuzzleException $e) {
            throw new RuntimeException('A guzzle exception has been raised', $e->getCode(), $e);
        } catch (\Exception $e) {
            throw new RuntimeException('A unexpected exception has been raised', $e->getCode(), $e);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        $data = array();

        foreach ($this as $key => $value) {
            if ($key === 'client') {
                continue;
            }
            $data[$key] = $value;
        }

        return json_encode((object) $data);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        $data = json_decode($serialized, true);

        if (! $data) {
            throw new RuntimeException('Unable to unserialize data');
        }

        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }

        $this->initClient();

        return $this;
    }

    /**
     * Inject a Guzzle\Http\CLient, for unit tests puprose
     *
     * @param Client $client
     *
     * @return WebHookReceipt
     */
    public function setClient(Client $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Return the current Guzzle\Http\Client, for unit tests purpose
     *
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Initialize the Guzzle\Http\Client
     */
    private function initClient()
    {
        $this->client = new Client();
    }
}
