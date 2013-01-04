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
    public static $multipartUploadChunk;
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
    private $options = array(
        'region' => Region::EU_WEST_1
    );
    /**
     * @var Boolean
     */
    private $delivered;

    public function __construct()
    {
        if (!class_exists('Aws\\S3\\S3Client')) {
            throw new RuntimeException('Aws SDK library version 2.0 or higher '
                . ' is required to use AmazonS3 delivery');
        }
    }

    /**
     * Constructor, will create a S3Client based on the given options
     *
     * @param $bucketName The bucket name
     * @param $objectKey The object key
     * @param array $options An array of S3Client options
     * @param string $acl A CannedAcl::* value
     * @param integer $uploadChunk The maximum size of a upload chunk
     */
    public static function create($bucketName, $objectKey, array $options, $acl = CannedAcl::PRIVATE_ACCESS, $uploadChunk = 10)
    {
        $obj = new static();

        $obj->setAcl($acl)
            ->setBucketName($bucketName)
            ->setObjectKey($objectKey)
            ->setMultipartUploadChunk($uploadChunk)
            ->addOptions($options);

        return $obj;
    }

    /**
     * @param int $multipartUploadChunk
     */
    public function setMultipartUploadChunk($multipartUploadChunk)
    {
        self::$multipartUploadChunk = $multipartUploadChunk;

        return $this;
    }

    /**
     * @return int
     */
    public static function GetMultipartUploadChunk()
    {
        return self::$multipartUploadChunk;
    }

    /**
     * @param string $acl
     */
    public function setAcl($acl)
    {
        $this->acl = $acl;

        return $this;
    }

    /**
     * @return string
     */
    public function getAcl()
    {
        return $this->acl;
    }

    /**
     * @param string $bucketName
     */
    public function setBucketName($bucketName)
    {
        $this->bucketName = $bucketName;

        return $this;
    }

    /**
     * @return string
     */
    public function getBucketName()
    {
        return $this->bucketName;
    }

    /**
     * @param boolean $delivered
     */
    public function setDelivered($delivered)
    {
        $this->delivered = $delivered;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getDelivered()
    {
        return $this->delivered;
    }

    /**
     * @param string $objectKey
     */
    public function setObjectKey($objectKey)
    {
        $this->objectKey = $objectKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getObjectKey()
    {
        return $this->objectKey;
    }

    /**
     * @param array $options
     */
    public function addOptions($options)
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
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
            $this->initClient();
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
        $this->initClient();
        $uploader = UploadBuilder::newInstance()
            ->setClient($this->client)
            ->setSource($pathfile)
            ->setBucket($this->bucketName)
            ->setKey($this->objectKey)
            ->setMinPartSize(self::$multipartUploadChunk * Size::MB)
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
            $this->initClient();
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

    /**
     * {@inheritdoc}
     */
    public static function fromArray(array $data)
    {
        return Factory::fromArray($data);
    }

    /**
     * Inject a S3Client, for unit tests puprose
     *
     * @param S3Client $client
     *
     * @return AmazonS3
     */
    public function setClient(S3Client $client = null)
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
        if ($this->client) {
            return;
        }

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
