<?php

/**
 * The DataFilters class filters NBA games data by team name, game status, or game date.
 *
 * The DataFilters class is a part of the NBA API and has a method called getFilteredGames.
 * This method uses three parameters: an array of games data, a search string (for team names
 * or game status), and a date. If a search string or date is provided, it filters the games
 * based on team names, game status, or the date of the game, and then returns the filtered games.
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

use App\Helpers\Dates;

class DataFilters
{
    /**
     * Filters game data based on team name, game status or game date.
     *
     * This method accepts an array of games, a search string, and a date.
     * It filters the game array based on whether the team's name (both visitor and home),
     * or the game status contains the search term, or the game's start date matches the
     * provided date. It returns the filtered games array.
     *
     * @param  array  $games      The array containing game data.
     * @param  string $search     The search term for team name or game status.
     * @param  string $date       The date for filtering games.
     * @return array              The filtered games array based on search term or date.
     */
    public static function getFilteredGames($games, $search, $date)
    {

        // If a search term is provided
        if ($search !== null) {
            /* @var array $games */
            // Use array_filter to iterate through the games array
            $games = array_filter($games, function ($game) use ($search) {
    
                /* @var string $search */
                // Check if the visitor team's name, the home team's name, or the game's status contains the search term
                return (isset($game['visitors_team_name']) && strpos($game['visitors_team_name'], $search) !== false)
                || (isset($game['home_team_name']) && strpos($game['home_team_name'], $search) !== false)
                || (isset($game['status']) && strpos($game['status'], $search) !== false);
            });
        }
    
        // If a date is provided
        if ($date !== null) {
            /* @var string $date */
            // Use array_filter to iterate through the games array
            $games = array_filter($games, function ($game) use ($date) {
    
                // Check if the game's start date matches the provided date after sanitizing it
                return Dates::sanitizeDate($game['date_start']) == $date;
            });
        }
    
        /* @var array $games */
        // Return the filtered games
        return $games;
    }
}
