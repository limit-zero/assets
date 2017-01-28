<?php

/*
 * This file is part of the limit0/assets package.
 *
 * (c) Limit Zero, LLC <contact@limit0.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Limit0\Assets\Tests;

use Limit0\Assets\AssetManager;
use Limit0\Assets\AssetFactory;

/**
 * Mocks and tests AssetManager functionality
 *
 * @author  Josh Worden <josh@limit0.io>
 */
class AssetManagerTest extends \PHPUnit_Framework_TestCase
{
    private $manager;

    private function getTestFilePath()
    {
        return sprintf('%s/asset-management.test', sys_get_temp_dir());
    }

    public function setUp()
    {
        file_put_contents($this->getTestFilePath(), 'Lorem ipsum dolor sit amet.');

        $engineMock = $this
            ->getMockBuilder('Limit0\Assets\StorageEngine\AmazonS3StorageEngine')
            ->setMethods(['store', 'retrieve', 'remove'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $engineMock
            ->expects($this->any())
            ->method('store')
            ->with($this->getAsset())
            ->willReturn(true);

        $identifier = 'some/path/new_file_name.txt';

        $engineMock
            ->expects($this->any())
            ->method('retrieve')
            ->with($identifier)
            ->willReturn($this->getAsset());

        $engineMock
            ->expects($this->any())
            ->method('remove')
            ->with($identifier)
            ->willReturn(false);

        $this->manager = new AssetManager();
        $this->manager->setStorageEngine($engineMock);
    }

    public function tearDown()
    {
        unlink($this->getTestFilePath());
    }

    private function getAsset()
    {
        return AssetFactory::createFromPath($this->getTestFilePath());
    }

    public function testStoreAsset()
    {
        $asset = $this->getAsset();
        $this->assertInternalType('boolean', $this->manager->store($asset, 'some/path', 'new_file_name.txt'), 'AssetManager::store must return boolean.');
    }

    /**
     *
     */
    public function testRetrieveAsset()
    {
        $this->assertInstanceOf(
            'Limit0\Assets\Asset',
            $this->manager->retrieve('some/path/new_file_name.txt'),
            'AssetManager::retrieve must return an Asset instance.'
        );
    }

    public function testRemoveAsset()
    {
        $this->assertInternalType('boolean', $this->manager->remove('some/path/new_file_name.txt'), 'AssetManager::remove must return boolean.');
    }
}
