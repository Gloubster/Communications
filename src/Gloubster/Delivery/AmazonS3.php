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

use Aws\Common\Enum\Region;
use Aws\Common\Aws;
use Aws\S3\Enum\CannedAcl;
use Aws\S3\S3Client;
use Aws\Common\Enum\Size;
use Aws\S3\Model\MultipartUpload\UploadBuilder;
use Aws\Common\Exception\MultipartUploadException;
use Aws\Common\Exception\AwsExceptionInterface;
use Gloubster\Exception\LogicException;
use Gloubster\Exception\RuntimeException;
use Guzzle\Common\Exception\GuzzleException;

class AmazonS3 implements DeliveryInterface
{
    /**
     * @var integer
     */
    public static $MULTIPART_UPLOAD_MB_CHUNK;
    /**
     * @var string
     */
    private $acl;
    /**
     * @var string
     */
    private $bucketName;
    /**
     * @var S3Client
     */
    private $client;
    /**
     * @var string
     */
    private $objectKey;
    /**
     * @var array
     */
    private $options;
    /**
     * @var Boolean
     */
    private $delivered;

    /**
     * Constructor, will create a S3Client based on the given options
     *
     * @param $bucketName The bucket name
     * @param $objectKey The object key
     * @param array $options An array of S3Client options
     * @param string $acl A CannedAcl::* value
     * @param integer $uploadChunk The maximum size of a upload chunk
     */
    public function __construct($bucketName, $objectKey, array $options, $acl = CannedAcl::PRIVATE_ACCESS, $uploadChunk = 10)
    {
        $this->acl                       = $acl;
        $this->bucketName                = $bucketName;
        $this->objectKey                 = $objectKey;
        $this->acl                       = $acl;
        self::$MULTIPART_UPLOAD_MB_CHUNK = $uploadChunk;

        $this->options = array_merge(
            array(
                 'region' => Region::EU_WEST_1
            ), $options
        );

        $this->initClient();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'amazon-s3';
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        if (! $this->delivered) {
            throw new LogicException('Data has not been delivered yet');
        }

        return sprintf('https://%s.s3.amazonaws.com/%s', $this->bucketName, $this->objectKey);
    }

    /**
     * {@inheritdoc}
     */
    public function deliverBinary($data)
    {
        try {
            $this->client->getCommand('PutObject', array(
                'Bucket' => $this->bucketName,
                'Key'    => $this->objectKey,
                'Body'   => $data,
                'ACL'    => $this->acl
            ));
            $this->delivered = true;
        } catch (AwsExceptionInterface $e) {
            throw new RuntimeException('An AmazonWebService exception occured', $e->getCode(), $e);
        } catch (\Exception $e) {
            throw new RuntimeException('An unexpected error occured', $e->getCode(), $e);
        }

        return $this->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function deliverFile($pathfile)
    {
        $uploader = UploadBuilder::newInstance()
            ->setClient($this->client)
            ->setSource($pathfile)
            ->setBucket($this->bucketName)
            ->setKey($this->objectKey)
            ->setMinPartSize(self::$MULTIPART_UPLOAD_MB_CHUNK * Size::MB)
            ->build();

        try {
            $uploader->upload();
            $this->delivered = true;
        } catch (MultipartUploadException $e) {
            $uploader->abort();
            throw new RuntimeException('File delivery failed', $e->getCode(), $e);
        } catch (AwsExceptionInterface $e) {
            throw new RuntimeException('An AmazonWebService exception occured', $e->getCode(), $e);
        } catch (\Exception $e) {
            throw new RuntimeException('An unexpected error occured', $e->getCode(), $e);
        }

        return $this->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function fetch($id)
    {
        try {
            $command = $this->client->getCommand('GetObject', array(
               'Bucket' => $this->bucketName,
               'Key'    => $this->objectKey,
            ));
            $result  = $command->execute();
        } catch (AwsExceptionInterface $e) {
            throw new RuntimeException('An AmazonWebService exception occured', $e->getCode(), $e);
        } catch (\Exception $e) {
            throw new RuntimeException('An unexpected error occured', $e->getCode(), $e);
        }

        return $result['Body'];
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
     * Inject a S3Client, for unit tests puprose
     *
     * @param S3Client $client
     *
     * @return AmazonS3
     */
    public function setClient(S3Client $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Return the current S3Client, for unit tests purpose
     *
     * @return S3Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Initialize the S3Client internally, base on the options
     *
     * @throws RuntimeException
     */
    private function initClient()
    {
        try {
            $this->client = S3Client::factory($this->options);
        } catch (AwsExceptionInterface $e) {
            throw new RuntimeException('An AmazonWebService exception has been raised', $e->getCode(), $e);
        } catch (GuzzleException $e) {
            throw new RuntimeException('A Guzzle exception has been raised', $e->getCode(), $e);
        } catch (\Exception $e) {
            throw new RuntimeException('An unexpected error occured', $e->getCode(), $e);
        }
    }
}
