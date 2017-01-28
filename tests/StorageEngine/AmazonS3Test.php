<?php

/*
 * This file is part of the limit0/assets package.
 *
 * (c) Limit Zero, LLC <contact@limit0.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Limit0\Assets\Tests\StorageEngine;

use Aws\S3\S3Client;
use Limit0\Assets\StorageEngine\AmazonS3StorageEngine;

/**
 * Configures the AmazonS3 StorageEngine
 *
 * @author  Josh Worden <josh@limit0.io>
 */
class AmazonS3Test extends AbstractStorageEngineTest
{
    public static function setUpBeforeClass()
    {
        if (!class_exists('Aws\S3\S3Client')) {
            self::markTestSkipped('AWS SDK not installed.');
        }

        if (false === getenv('AWS_ACCESS_KEY_ID') || false === getenv('AWS_SECRET_ACCESS_KEY')) {
            self::markTestSkipped('AWS Credentials not present!');
        }

        if (false === $bucket = getenv('AWS_S3_BUCKET_NAME')) {
            self::markTestSkipped('AWS Bucket Name is not present!');
        }

        self::$engine = new AmazonS3StorageEngine();
        self::$engine->setBucket($bucket)->setAcl('public-read');
    }
}
