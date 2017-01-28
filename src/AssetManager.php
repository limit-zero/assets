<?php

/*
 * This file is part of the limit0/assets package.
 *
 * (c) Limit Zero, LLC <contact@limit0.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Limit0\Assets;

/**
 * The AssetManager provides a wrapper for your configured storage engine.
 *
 * @author  Josh Worden <josh@limit0.io>
 */
class AssetManager
{
    /**
     * @var     StorageEngineInterface
     */
    private $storageEngine;

    /**
     * Returns the storage engine
     *
     * @throws  Exception\ManagerException
     * @return  StorageEngineInterface
     */
    public function getStorageEngine()
    {
        if (null === $this->storageEngine) {
            throw Exception\ManagerException::missingStorageEngine();
        }
        return $this->storageEngine;
    }

    /**
     * Removes an asset
     *
     * @param   string  $identifier
     */
    public function remove($identifier)
    {
        return $this->getStorageEngine()->remove($identifier);
    }

    /**
     * Retrieves an asset
     *
     * @param   string  $identifier
     * @return  Asset
     */
    public function retrieve($identifier)
    {
        return $this->getStorageEngine()->retrieve($identifier);
    }

    /**
     * Stores an asset
     *
     * @param   Asset   $asset
     * @return  Asset
     */
    public function store(Asset $asset, $path = null, $filename = null)
    {
        return $this->getStorageEngine()->store($asset, $path, $filename);
    }

    /**
     * Sets the storage engine
     *
     * @param   StorageEngineInterface
     */
    public function setStorageEngine(StorageEngineInterface $storageEngine)
    {
        $this->storageEngine = $storageEngine;
        return $this;
    }
}
