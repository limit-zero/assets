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

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * An Asset model extends the functionality available in \SplFileInfo and UploadedFile
 *
 * @author  Josh Worden <josh@limit0.io>
 */
class Asset extends UploadedFile
{
    /**
     * @var array
     */
    private $storageMetadata = [];

    /**
     * Override UploadedFile constructor
     */
    public function __construct()
    {
    }

    public function __toString()
    {
        return $this->pathname;
    }

    /**
     * Returns the filename property.
     *
     * @return array
     */
    public function getStorageMetadata()
    {
        return $this->storageMetadata;
    }

    /**
     * Returns the filename property.
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Returns the filepath property.
     *
     * @return string
     */
    public function getFilepath()
    {
        return $this->filepath;
    }

    /**
     * Returns the extension property.
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Returns the pathname property.
     *
     * @return string
     */
    public function getPathname()
    {
        return $this->pathname;
    }

    /**
     * Returns the mimeType property.
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Returns the clientOriginalName property.
     *
     * @return string
     */
    public function getClientOriginalName()
    {
        return $this->clientOriginalName;
    }

    /**
     * Returns the clientOriginalExtension property.
     *
     * @return string
     */
    public function getClientOriginalExtension()
    {
        return $this->clientOriginalExtension;
    }

    /**
     * Returns the clientMimeType property.
     *
     * @return string
     */
    public function getClientMimeType()
    {
        return $this->clientMimeType;
    }

    /**
     * Returns the filename property.
     *
     * @param  array $value
     * @return self
     */
    public function setStorageMetadata(array $value = [])
    {
        $this->storageMetadata = $value;
        return $this;
    }

    /**
     * Sets the filename property.
     *
     * @param  string $value
     * @return self
     */
    public function setFilename($value)
    {
        $this->filename = $value;
        return $this;
    }

    /**
     * Sets the filepath property.
     *
     * @param  string $value
     * @return self
     */
    public function setFilepath($value)
    {
        $this->filepath = $value;
        return $this;
    }

    /**
     * Sets the extension property.
     *
     * @param  string $value
     * @return self
     */
    public function setExtension($value)
    {
        $this->extension = $value;
        return $this;
    }

    /**
     * Sets the pathname property.
     *
     * @param  string $value
     * @return self
     */
    public function setPathname($value)
    {
        $this->pathname = $value;
        return $this;
    }

    /**
     * Sets the mimeType property.
     *
     * @param  string $value
     * @return self
     */
    public function setMimeType($value)
    {
        $this->mimeType = $value;
        return $this;
    }

    /**
     * Sets the clientOriginalName property.
     *
     * @param  string $value
     * @return self
     */
    public function setClientOriginalName($value)
    {
        $this->clientOriginalName = $value;
        return $this;
    }

    /**
     * Sets the clientOriginalExtension property.
     *
     * @param  string $value
     * @return self
     */
    public function setClientOriginalExtension($value)
    {
        $this->clientOriginalExtension = $value;
        return $this;
    }

    /**
     * Sets the clientMimeType property.
     *
     * @param  string $value
     * @return self
     */
    public function setClientMimeType($value)
    {
        $this->clientMimeType = $value;
        return $this;
    }
}
