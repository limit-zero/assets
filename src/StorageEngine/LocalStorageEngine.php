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

use Limit0\Assets\Asset;
use Limit0\Assets\AssetFactory;
use Limit0\Assets\Exception\StorageException;
use Limit0\Assets\StorageEngineInterface;

/**
 * This class supports storing, retrieve, and deleting files to a local server path
 *
 * @author  Josh Worden <josh@limit0.io>
 */
class LocalStorageEngine implements StorageEngineInterface
{
    /**
     * @var     string
     */
    private $path;

    /**
     * @return  string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($identifier)
    {
        $rootPath = sprintf('%s/%s', $this->getPath(), $identifier);

        if (!file_exists($rootPath)) {
            throw new StorageException(sprintf('File %s could not be found in %s', $identifier, $this->getPath()));
        }

        return unlink($rootPath);
    }

    /**
     * {@inheritdoc}
     */
    public function retrieve($identifier)
    {
        $rootPath = sprintf('%s/%s', $this->getPath(), $identifier);

        if (!file_exists($rootPath)) {
            throw new StorageException(sprintf('File %s could not be found in %s', $identifier, $this->getPath()));
        }

        $path = explode('/', $identifier);
        $fileName = array_pop($path);
        $localPath = sprintf('%s/%s', sys_get_temp_dir(), $fileName);

        $asset = AssetFactory::createFromPath($rootPath);
        $asset->setFilename($fileName)->setFilepath(implode('/', $path));
        return $asset;
    }

    /**
     * Sets the path assets will be stored under
     *
     * @param   string
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function store(Asset $asset, $path = null, $filename = null)
    {
        $filename = (null === $filename) ? $asset->getBasename() : $filename;
        $dir = sprintf('%s/%s', $this->getPath(), $path);
        $key = sprintf('%s/%s', $dir, $filename);

        if (!is_dir($dir) && true !== @mkdir($dir, 0777, true)) {
            $error = error_get_last();
            throw new StorageException($error['message']);
        }

        file_put_contents($key, $asset);

        return true;
    }
}
