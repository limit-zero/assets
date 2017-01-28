<?php

/*
 * This file is part of the limit0/assets package.
 *
 * (c) Limit Zero, LLC <contact@limit0.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Limit0\Assets\StorageEngine;

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Limit0\Assets\Asset;
use Limit0\Assets\AssetFactory;
use Limit0\Assets\Exception\StorageException;
use Limit0\Assets\StorageEngineInterface;

/**
 * This class supports storing, retrieve, and deleting files to Amazon S3 storage.
 * At the moment, no additional storage parameters can be set -- it's best practice
 * to make these the defaults for your bucket to prevent objects being out of sync
 * with your AWS policies.
 *
 * Per AWS best practices, this passes through the authentication of requests to
 * the AWS SDK itself, for more info:
 * @see http://docs.aws.amazon.com/aws-sdk-php/v3/guide/guide/credentials.html#environment-credentials
 *
 * @see setBucket   The bucket must be set when configuring this storage engine.
 * @see setAcl      The default ACL is `private`.
 *
 * @author  Josh Worden <josh@limit0.io>
 */
class AmazonS3StorageEngine implements StorageEngineInterface
{
    /**
     * @var     string
     */
    private $bucket;

    /**
     * @var     string
     */
    private $acl = 'private';

    /**
     * @var     S3Client
     */
    private $client;

    /**
     * @var     string
     */
    private $region = 'us-east-1';

    /**
     * @return  string
     */
    public function getAcl()
    {
        return $this->acl;
    }

    /**
     * @return  string
     */
    public function getBucket()
    {
        if (null === $this->bucket) {
            throw StorageException::invalidConfiguration('bucket');
        }
        return $this->bucket;
    }

    /**
     * Instantiates and returns an S3Client instance
     * @return  S3Client
     */
    public function getClient()
    {
        if (null === $this->client) {
            $this->client = S3Client::factory([
                'version'   => 'latest',
                'region'    => $this->getRegion()
            ]);
        }
        return $this->client;
    }

    /**
     * @return  string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($identifier)
    {
        try {
            $this->getClient()->deleteObject([
                'Bucket'    => $this->getBucket(),
                'Key'       => $identifier
            ]);
        } catch (S3Exception $e) {
            throw new StorageException($e->getMessage());
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function retrieve($identifier)
    {
        $path = explode('/', $identifier);
        $fileName = array_pop($path);
        $localPath = sprintf('%s/%s', sys_get_temp_dir(), $fileName);

        try {
            $this->getClient()->getObject([
                'Bucket' => $this->getBucket(),
                'Key'    => $identifier,
                'SaveAs' => $localPath
            ]);

            $asset = AssetFactory::createFromPath($localPath);
            $asset->setFilename($fileName)->setFilepath(implode('/', $path));
            return $asset;

        } catch (S3Exception $e) {
            throw new StorageException($e->getMessage());
        }
    }

    /**
     * Sets the ACL the asset should be stored with.
     *
     * @param   string
     */
    public function setAcl($acl)
    {
        $this->acl = $acl;
        return $this;
    }

    /**
     * Sets the bucket the assets should be stored to.
     *
     * @param   string
     */
    public function setBucket($bucket)
    {
        $this->bucket = $bucket;
        return $this;
    }

    /**
     * Override the S3 region to connect to
     * @param   string
     */
    public function setRegion($region)
    {
        $this->region = $region;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function store(Asset $asset, $path = null, $filename = null)
    {
        $filename = (null === $filename) ? $asset->getBasename() : $filename;
        $key = sprintf('%s/%s', $path, $filename);

        try {
            $this->getClient()->putObject([
                'ACL'           => $this->getAcl(),
                'Bucket'        => $this->getBucket(),
                'Key'           => $key,
                'ContentType'   => $asset->getMimeType(),
                'Body'          => fopen($asset->getPathname(), 'r')
            ]);

        } catch (S3Exception $e) {
            throw new StorageException($e->getMessage());
        }

        return true;
    }
}
