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

use Gloubster\Message\Job\JobInterface;
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

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    public function getParameter()
    {
        return $this->parameter;
    }

    public function setParameter($parameter)
    {
        $this->parameter = $parameter;

        return $this;
    }

    public function getName()
    {
        return 'web-hook';
    }

    public function setUseBody($boolean)
    {
        $this->useBody = (Boolean) $boolean;

        return $this;
    }

    public function getUseBody()
    {
        return $this->useBody;
    }

    /**
     * {@inheritdoc}
     */
    public function acknowledge(JobInterface $job)
    {
        if (!$this->client) {
            $this->initClient();
        }

        $data = $job->toJson();
        try {
            $request = $this->client->post($this->url, array('Content-Type' => 'application/json'), $this->useBody ? $data : null);

            if ($this->parameter) {
                $request->setPostField($this->parameter, $data);
            }
            $request->send();
        } catch (GuzzleException $e) {
            throw new RuntimeException('A guzzle exception has been raised', $e->getCode(), $e);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            throw new RuntimeException('A unexpected exception has been raised', $e->getCode(), $e);
        }

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
     * {@inheritdoc}
     */
    public function toArray()
    {
        $data = array('name' => $this->getName());

        foreach ($this as $key => $value) {
            if ('client' === $key) {
                continue;
            }
            $data[$key] = $value;
        }

        return $data;
    }

    public static function create($url, $parameter, $useBody = false)
    {
        $hook = new WebHookReceipt();

        return $hook->setUrl($url)
                ->setParameter($parameter)
                ->setUseBody($useBody);
    }

    /**
     * {@inheritdoc}
     */
    public static function fromArray(array $data)
    {
        return Factory::fromArray($data);
    }

    /**
     * Initialize the Guzzle\Http\Client
     */
    private function initClient()
    {
        $this->client = new Client();
    }
}
