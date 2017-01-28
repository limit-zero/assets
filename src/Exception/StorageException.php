<?php

/*
 * This file is part of the limit0/assets package.
 *
 * (c) Limit Zero, LLC <contact@limit0.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Limit0\Assets\Exception;

/**
 * Exceptions emitting from the StorageEngine classes.
 *
 * @author  Josh Worden <josh@limit0.io>
 */
class StorageException extends \Exception
{
    public static function invalidConfiguration($message = 'unknown')
    {
        return new self('Invalid StorageEngineConfiguration: '.$message);
    }
}
