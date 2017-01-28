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

use Limit0\Assets\Asset;
use Limit0\Assets\AssetFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Tests basic AssetFactory functions
 *
 * @author  Josh Worden <josh@limit0.io>
 */
class AssetFactoryTest extends \PHPUnit_Framework_TestCase
{
    private function getTestFilePath()
    {
        return sprintf('%s/asset-management.test', sys_get_temp_dir());
    }

    public function setUp()
    {
        file_put_contents($this->getTestFilePath(), 'Lorem ipsum dolor sit amet.');
    }

    public function tearDown()
    {
        unlink($this->getTestFilePath());
    }

    public function testAssetPropertiesFromPath()
    {
        $asset = AssetFactory::createFromPath($this->getTestFilePath());

        $this->assertEquals('asset-management.test', $asset->getFilename());
        $this->assertEquals('test', $asset->getExtension());
        $this->assertEquals($this->getTestFilePath(), $asset->getPathname());
        $this->assertEquals('text/plain', $asset->getMimeType());
    }

    public function testAssetPropertiesFromSplFileInfo()
    {
        $file = new \SplFileInfo($this->getTestFilePath());
        $asset = AssetFactory::createFromSplFileInfo($file);

        $this->assertEquals('asset-management.test', $asset->getFilename());
        $this->assertEquals('test', $asset->getExtension());
        $this->assertEquals($this->getTestFilePath(), $asset->getPathname());
        $this->assertEquals('text/plain', $asset->getMimeType());
    }

    public function testAssetPropertiesFromUploadedFile()
    {
        $clientName = 'client_original_name.doc';
        $clientType = 'application/banana';
        $file = new UploadedFile($this->getTestFilePath(), $clientName, $clientType);
        $asset = AssetFactory::createFromUploadedFile($file);

        $this->assertEquals('asset-management.test', $asset->getFilename());
        $this->assertEquals('test', $asset->getExtension());
        $this->assertEquals($this->getTestFilePath(), $asset->getPathname());
        $this->assertEquals('text/plain', $asset->getMimeType());

        $this->assertEquals($clientName, $asset->getClientOriginalName());
        $this->assertEquals('doc', $asset->getClientOriginalExtension());
        $this->assertEquals($clientType, $asset->getClientMimeType());
    }

    /**
     * @expectedException        Limit0\Assets\Exception\FactoryException
     * @expectedExceptionCode    0
     * @expectedExceptionMessage Unable to open file /tmp/file/does/not/exist
     */
    public function testAssetInvalidFile()
    {
        AssetFactory::createFromPath('/tmp/file/does/not/exist');
    }

    public function testCustomPropertyStorage()
    {
        $asset = AssetFactory::createFromPath($this->getTestFilePath());

        $filePath = 'some/test/value';

        $asset->setFilePath($filePath);

        $this->assertEquals($filePath, $asset->getFilePath());
    }

    /**
     *
     */
    public function testAssetInheritance()
    {
        $asset = AssetFactory::createFromPath($this->getTestFilePath());
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\File\UploadedFile', $asset);
        $this->assertInstanceOf('SplFileInfo', $asset);
    }
}
