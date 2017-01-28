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

use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * The AssetFactory is responsible for creating Asset instances from files or paths
 *
 * @author  Josh Worden <josh@limit0.io>
 */
class AssetFactory
{
    /**
     * Creates an Asset from a file path
     *
     * @param   string          $path
     * @throws  Exception\FactoryException  If the file could not be loaded
     * @return  Asset
     */
    public static function createFromPath($path)
    {
        if (!file_exists($path)) {
            throw new Exception\FactoryException(sprintf('Unable to open file %s', $path));
        }
        $file = new \SplFileInfo($path);
        return static::createFromSplFileInfo($file);
    }

    /**
     * Creates an Asset from a \SplFileInfo wrapper
     *
     * @param   \SplFileInfo    $file
     * @throws  Exception\FactoryException  If the file could not be loaded
     * @return  Asset
     */
    public static function createFromSplFileInfo(\SplFileInfo $file)
    {
        $asset = new Asset();
        $guesser = MimeTypeGuesser::getInstance();
        return $asset
            ->setFilename($file->getFilename())
            ->setExtension($file->getExtension())
            ->setPathname($file->getPathname())
            ->setMimeType($guesser->guess($file->getPathname()))
            ->setClientOriginalName($file->getFilename())
            ->setClientOriginalExtension($file->getExtension())
        ;
    }

    /**
     * Creates an Asset from an UploadedFile instance
     *
     * @param   UploadedFile    $file
     * @throws  Exception\FactoryException  If the file could not be loaded
     * @return  Asset
     */
    public static function createFromUploadedFile(UploadedFile $file)
    {
        $asset = new Asset();
        $guesser = MimeTypeGuesser::getInstance();
        return $asset
            ->setFilename($file->getFilename())
            ->setExtension($file->getExtension())
            ->setPathname($file->getPathname())
            ->setMimeType($guesser->guess($file->getPathname()))
            ->setClientOriginalName($file->getClientOriginalName())
            ->setClientOriginalExtension($file->getClientOriginalExtension())
            ->setClientMimeType($file->getClientMimeType())
        ;
    }

    /**
     * Creates an Asset from a URI
     *
     * @param   string          $uri
     * @throws  Exception\FactoryException  If the file could not be loaded
     * @return  Asset
     */
    public static function createFromUri($uri)
    {
        $name = substr($uri, strrpos($uri, '/'));
        $path = sprintf('%s/%s', sys_get_temp_dir(), $name);
        copy($uri, $path);
        return static::createFromPath($path);
    }
}
