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

use Limit0\Assets\AssetFactory;

/**
 * Provides common tests for all storage engines
 *
 * @author  Josh Worden <josh@limit0.io>
 */
abstract class AbstractStorageEngineTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var     \Limit0\Assets\StorageEngineInterface
     */
    protected static $engine;

    public function testEngineSetUp()
    {
        $this->assertInstanceOf('Limit0\Assets\StorageEngineInterface', self::$engine, 'Storage engine was not set up before test.');
    }

    public function testEngineStorage()
    {
        $result = self::$engine->store(
            $this->getTestAsset(),
            $this->getTestPath(),
            $this->getTestFilename()
        );

        $this->assertTrue($result, 'StorageEngine did not return a good value.');
    }

    public function testEngineRetrieval()
    {
        $asset = self::$engine->retrieve($this->getTestIdentifier());
        $this->assertInstanceOf('Limit0\Assets\Asset', $asset);
    }

    public function testEngineRemoval()
    {
        $result = self::$engine->remove($this->getTestIdentifier());
        $this->assertTrue($result, 'StorageEngine did not return a good value.');
    }

    protected function getTestAsset()
    {
        $path = sprintf('%s/asset-management.test', sys_get_temp_dir());
        file_put_contents($path, 'Lorem ipsum dolor sit amet.');
        return AssetFactory::createFromPath($path);
    }

    protected function getTestPath()
    {
        return 'test/directory/structure';
    }

    protected function getTestFilename()
    {
        return 'asset-management.test';
    }

    protected function getTestIdentifier()
    {
        return sprintf('%s/%s', $this->getTestPath(), $this->getTestFilename());
    }
}
