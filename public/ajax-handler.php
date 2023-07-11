<?php
/**
 * This file handles AJAX requests for NBA games data and implements caching for performance optimization.
 *
 * This file serves as an intermediary between the client-side application and the server-side data generation.
 * When a request is received, it first checks if the data exists in the cache. If it does, the cached data is
 * returned. Otherwise, it calls the server-side script to generate the data and save it to the cache.
 *
 * This file accepts five parameters: gamesPerPage (number of games to show per page), orderby (column to order by),
 * page (the current page number), date (the date to filter games by), and search (a string to filter games by).
 *
 * PHP version 8.1
 *
 * @category   NBADataHandling
 * @package    NBA_API
 * @subpackage Ajax_Handler
 * @author     Harry Ji <jiharry@hotmail.com>
 * @license    https://www.opensource.org/licenses/mit-license.html  MIT License
 * @link       https://github.com/harryji168/NBA-API
 * @since      File available since Release 1.0.0
 */
 
session_start();

// Get the parameters
$gamesPerPage = isset($_GET['gamesPerPage']) ? $_GET['gamesPerPage'] : 'default';
$orderby = isset($_GET['orderby']) ? $_GET['orderby'] : 'default';
$page = isset($_GET['page']) ? $_GET['page'] : 'default';
$date = isset($_GET['date']) ? $_GET['date'] : 'default';
$search = isset($_GET['search']) ? $_GET['search'] : 'default';

// Hash the parameters to form a cache file name
$filePath = '../storage/cache/';
$cacheFileName = 'cache_' . md5($gamesPerPage . $orderby . $page . $date . $search) . '.html';


// Check if the cache file exists
if (file_exists($filePath . $cacheFileName)) {
    // Serve the cache file
    echo file_get_contents($filePath . $cacheFileName);
} else {
    // Include the PHP file from outside of the public directory
    require_once '../resources/views/nba-game-table.php';
}
