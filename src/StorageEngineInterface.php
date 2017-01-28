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
 * This interface defines the publicly accessible methods available in userland
 * implementations of a StorageEngine class.
 *
 * This interface MUST be implemented for injection into the AssetManager.
 *
 * If any error is encountered, an Exception\StorageException should be emitted.
 *
 * @author  Josh Worden <josh@limit0.io>
 */
interface StorageEngineInterface
{
    /**
     * Removes an Asset
     *
     * @param   mixed   $identifier      The asset identifier (typically, directory and filename)
     *
     * @throws  Exception\StorageException
     * @return  boolean
     */
    public function remove($identifier);

    /**
     * Retrieves a stored asset
     *
     * @param   mixed   $identifier     The asset identifier
     *
     * @throws  Exception\StorageException
     * @return  Asset
     */
    public function retrieve($identifier);

    /**
     * Stores an Asset
     *
     * @param   Asset   $asset      The Asset to store
     * @param   string  $path       The path to store the asset in
     * @param   string  $fileName   The filename to store the asset as
     *
     * @throws  Exception\StorageException
     * @return  boolean
     */
    public function store(Asset $asset, $path = null, $fileName = null);
}
