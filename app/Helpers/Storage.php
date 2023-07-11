<?php

/**
 * The Storage class provides methods for managing the storage path.
 *
 * The Storage class is a utility part of the application and has two methods:
 * path and ensureDirectoryExists. The 'path' method generates a storage path
 * by appending a provided path to the base storage path. It also ensures that
 * the directory for this path exists by calling the 'ensureDirectoryExists'
 * method. The 'ensureDirectoryExists' method creates a directory if it does
 * not exist.
 *
 * PHP version 8.1
 *
 * @category   NBADataFiltering
 * @package    NBA_API
 * @author     Harry Ji <jiharry@hotmail.com>
 * @license    https://www.opensource.org/licenses/mit-license.html  MIT License
 * @link       https://github.com/harryji168/NBA-API
 */

namespace App\Helpers;

class Storage
{
     /**
     * Generate a storage path and ensure its directory exists.
     *
     * @param string $path A subpath to be appended to the base storage path.
     *
     * @return string The full storage path.
     */
    public static function path($path = '')
    {
        // Define the base storage path
        $storageBasePath = __DIR__ . '/../../storage';

        // Append the provided path, if any
        if (!empty($path)) {
            $storageBasePath .= '/' . trim($path, '/');
        }

        // Create the directory if it doesn't exist
        self::ensureDirectoryExists($storageBasePath);

        // Return the full path
        return $storageBasePath;
    }

    /**
     * Ensure a directory exists.
     *
     * @param string $path The path to the directory.
     */
    private static function ensureDirectoryExists($path)
    {
        $directoryPath = dirname($path);

        if (!is_dir($directoryPath)) {
            mkdir($directoryPath, 0777, true);
        }
    }
}
