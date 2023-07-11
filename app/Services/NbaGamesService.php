<?php

/**
 * The NbaGamesService is used to fetch NBA games data from an external API.
 *
 * This service fetches data from an NBA API. It initializes API host,
 * key and endpoint for games from environment variables,
 * then fetches the data for each season, and handles rate limits and exceptions.
 * The data is saved in a cache file for future use,
 * to avoid unnecessary API requests. If the USE_CACHE environment variable is
 * set to 'true' and the cache file exists, the data
 * will be fetched from the cache file instead of the API.
 *
 * PHP version 8.1
 *
 * @category   NBAGamesDataFetching
 * @package    NBA_API
 * @author     Harry Ji <jiharry@hotmail.com>
 * @license    https://www.opensource.org/licenses/mit-license.html  MIT License
 * @link       https://github.com/harryji168/NBA-API
 */

namespace App\Services;

use Config\Database;
use App\Helpers\Storage;
use App\Services\NbaSeasonsService;
use Exception;

class NbaGamesService
{
    /**
     * NBA API host value from environment variable.
     *
     * @var    string
     */
    protected $apiHost;

    /**
     * NBA API key value from environment variable.
     *
     * @var    string
     */
    protected $apiKey;

    /**
     * NBA API endpoint for games from environment variable.
     *
     * @var    string
     */
    protected $apiEndpointGames;

    /**
     * Instance of NBA API requester.
     *
     * @var    NbaApiRequester
     */
    protected $requester;

    /**
     * Instance of NBA Seasons service.
     *
     * @var    NbaSeasonsService
     */
    private $nbaSeasonsService;


    private $conn;
    /**
     * Initialize properties from environment variables and instantiate NBA Seasons service.
     */
    public function __construct()
    {
        $this->apiHost = $_ENV['API_HOST'];
        $this->apiKey = $_ENV['API_KEY'];
        $this->apiEndpointGames = $_ENV['API_ENDPOINT_GAMES'];
        $this->nbaSeasonsService = new NbaSeasonsService();
        $this->conn = (new Database())->getConnection();
    }

    /**
     * Fetch game data from the NBA API.
     *
     * @return array $games Game data from the NBA API.
     * @throws Exception if unable to fetch seasons data or any other error occurs
     */
    public function fetchGameData()
    {
        $_ENV['USE_CACHE'] = 'true';
        // Retrieve seasons from the NBA seasons service
        $seasons = $this->nbaSeasonsService->getSeasons();

        // Check if the retrieved seasons are not empty. If they are, throw an exception.
        if (empty($seasons)) {
            throw new Exception('Unable to fetch seasons data');
        }

        // Initialize an empty games array which will be used to store game data later
        $games = [];

        foreach ($seasons as $season) {
            $season ='2022';
            $filepath = Storage::path('json/'.$season.'-games.json');

            // If the cache file exists and the USE_CACHE environment variable is set to 'true'
            // Load the data from the cache file instead of making a new request
            if (file_exists($filepath) && $_ENV['USE_CACHE'] === 'true') {
                // Get the file content and decode the JSON
                $data = json_decode(file_get_contents($filepath), true);

                if (isset($data['message']) && strpos($data['message'], 'rate limit') !== false) {
                    // Handle rate limit error, log this information
                    error_log('Rate limit exceeded: ' . $data['message']);
                    // Check if the 'response' key exists in the data and it's not empty
                } elseif (isset($data['response']) && count($data['response']) > 0) {
                    foreach ($data['response'] as $index => $game) {
                        // Add the 'season' field to each game
                        $data['response'][$index]['season'] = $season;
                    }

                    // If $games is not previously defined, initialize it as an empty array
                    if (!isset($games)) {
                        $games = [];
                    }

                    // Merge the current response with the games array
                    $games = array_merge($games, $data['response']);

                    $processor = new GameDataProcessor($this->conn);
                    $number_games_season = $processor->getGamesBySeason($season);
                   
                    if ($number_games_season == sizeof($data['response'])) {
                        continue;
                    }
                    $games = $data['response'];
                    usort($games, function ($a, $b) {
                        return $b['id'] <=> $a['id'];
                    });
                    foreach ($games as $game) {
                        echo    $game['date']['start'].'<br>';
                      
                        $visitors_team_id = $processor->processTeamData($game['teams']['visitors'], 'visitors');

                        $home_team_id = $processor->processTeamData($game['teams']['home'], 'home');
                        $arena_id = $processor->processArenaData($game['arena']);
                        $game_id = $processor->processGameData(
                            $game,
                            $home_team_id,
                            $visitors_team_id,
                            $arena_id,
                            $season
                        );
                    
                        $processor->processLineScores($game_id, $home_team_id, $game['scores']['home']['linescore']);
                        $processor->processLineScores(
                            $game_id,
                            $visitors_team_id,
                            $game['scores']['visitors']['linescore']
                        );
                    }
                            
                    // Skip the rest of the current iteration and proceed to the next season in the loop
                    continue;
                }
            }

            try {
                // Instantiate a new NBA API Requester with the endpoint for the current season
                $this->requester = new NbaApiRequester(
                    $this->apiEndpointGames . $season,
                    [
                        'Accept' => 'application/json',
                        'X-RapidAPI-Host' => $this->apiHost,
                        'X-RapidAPI-Key' => $this->apiKey
                    ]
                );

                // Request the data from the NBA API
                $response = $this->requester->request();

                // Check if the response is not empty
                if ($response) {
                    // Decode the JSON response
                    $data = json_decode($response, true);

                    if (isset($data['message']) && strpos($data['message'], 'rate limit') !== false) {
                        throw new Exception('Rate limit exceeded: ' . $data['message']);
                    } elseif (isset($data['response']) && count($data['response']) > 0) {
                        // Store the response data in the cache file for future use
                        file_put_contents($filepath, $response);
                    }

                    // Check if the 'response' key exists in the data and merge it with the existing $games array
                    if (isset($data['response'])) {
                        // If $games is not previously defined, initialize it as an empty array
                        if (!isset($games)) {
                            $games = [];
                        }

                        // Merge the current response with the games array
                        $games = array_merge($games, $data['response']);
                    } else {
                        throw new Exception("The 'response' key is missing in the API data.");
                    }
                } else {
                    throw new Exception("The response from the NBA API is empty.");
                }
            } catch (Exception $e) {
                // Handle the exception by throwing a new one with more context
                throw new Exception('Error fetching data from the NBA API: ' . $e->getMessage());
            }

            // Sleep for 1 seconds to avoid hitting the API rate limit
            sleep(1);
        }

        return $games;
    }
}
