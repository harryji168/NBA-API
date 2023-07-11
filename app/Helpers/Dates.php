<?php

/**
 * The Dates class provides methods to sanitize and format date strings.
 *
 * The Dates class is part of the NBA API and includes two methods: sanitizeDate and sanitizeDateTime.
 * Both methods receive a date string as a parameter. The sanitizeDate method returns the date in
 * 'Y-m-d' format or null if the provided string is not a valid date. The sanitizeDateTime method
 * returns the date and time in 'Y-m-d H:i T' format or null for invalid dates.
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

class Dates
{
    /**
     * Sanitize and format a date string.
     *
     * @param  string $date The date string to sanitize and format.
     * @return string|null The sanitized date string in 'Y-m-d' format or null for invalid dates.
     */
    public static function sanitizeDate($date)
    {
        if (!empty($date)) {
            $time = strtotime($date);
            if ($time === false) {
                // Invalid date string, return null
                return null;
            } else {
                // Valid date, return as Y-m-d format
                return date('Y-m-d', $time);
            }
        }
        return null;
    }

    /**
     * Sanitize and format a date and time string.
     *
     * @param  string $date The date and time string to sanitize and format.
     * @return string|null The sanitized date and time string in 'Y-m-d H:i T' format or null for invalid dates.
     */
    public static function sanitizeDateTime($date)
    {
        if (!empty($date)) {
            $time = strtotime($date);
            if ($time === false) {
                // Invalid date string, return null
                return null;
            } else {
                // Valid date, return as 'Y-m-d H:i T' format
                return date('Y', $time).'-'.date('m-d', $time).' '.date('H:i T', $time);
            }
        }
        return null;
    }
}
