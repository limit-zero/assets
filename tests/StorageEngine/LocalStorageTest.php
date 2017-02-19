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

use Limit0\Assets\StorageEngine\LocalStorageEngine;

/**
 * Configures the AmazonS3 StorageEngine
 *
 * @author  Josh Worden <josh@limit0.io>
 */
class LocalStorageTest extends AbstractStorageEngineTest
{
    public static function setUpBeforeClass()
    {
        self::$engine = new LocalStorageEngine();
        self::$engine->setPath(sprintf('%s/%s', sys_get_temp_dir(), 'l0-asset-test'));
    }
}
