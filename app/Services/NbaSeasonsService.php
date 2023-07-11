<?php

/**
 * The NbaSeasonsService class fetches NBA seasons data from an external API.
 *
 * NbaSeasonsService retrieves data about NBA seasons from an external API. It leverages the NbaApiRequester
 * to make the actual HTTP requests. The class uses environment variables to set the API host, API key, and API
 * endpoint for seasons. The data fetched is cached in a local file and is preferentially retrieved from
 * this cache (if available) to save on API requests.
 *
 * @category   NBADataRetrieval
 * @package    NBA_API
 * @author     Harry Ji <jiharry@hotmail.com>
 * @license    https://www.opensource.org/licenses/mit-license.html  MIT License
 * @link       https://github.com/harryji168/NBA-API
 */

namespace App\Services;

use App\Helpers\Storage;
use App\Services\NbaApiRequester;
use Exception;

class NbaSeasonsService
{
    /**
     * The host of the NBA API.
     *
     * @var    string
     */
    protected $apiHost;

    /**
     * The key used to access the NBA API.
     *
     * @var    string
     */
    protected $apiKey;

    /**
     * The endpoint of the NBA API for retrieving season data.
     *
     * @var    string
     */
    protected $apiEndpointSeasons;

    /**
     * The service used to make requests to the NBA API.
     *
     * @var    NbaApiRequester
     */
    protected $requester;


    public function __construct()
    {
        // Assign values from the environment variables to local properties
        $this->apiHost = $_ENV['API_HOST'];
        $this->apiKey = $_ENV['API_KEY'];
        $this->apiEndpointSeasons = $_ENV['API_ENDPOINT_SEASONS'];
        // Create a new NBA API Requester with the Seasons endpoint and the necessary headers
        $this->requester = new NbaApiRequester($this->apiEndpointSeasons, [
            'Accept' => 'application/json',
            'X-RapidAPI-Host' => $this->apiHost,
            'X-RapidAPI-Key' => $this->apiKey
        ]);
    }

    /**
     * Fetch game data from the NBA API.
     * The method first checks if a cached version of the data exists. If so, it returns this data.
     * If no cache is available, it makes a new API request. It then saves the response
     * in the cache file for future use.
     *
     * @return array|null  An array containing the seasons data, or null if no data was found
     *
     * @throws Exception if unable to fetch seasons data or any other error occurs
     */
    public function getSeasons()
    {
        // Define the path for the cache file
        $filepath = Storage::path('json/seasons.json');

        // If the cache file exists and the USE_CACHE environment variable is set to 'true'
        // Load the data from the cache file instead of making a new request
        if (file_exists($filepath) && $_ENV['USE_CACHE'] === 'true') {
            $data = json_decode(file_get_contents($filepath), true);
            if (isset($data['message']) && strpos($data['message'], 'rate limit') !== false) {
                throw new Exception('Rate limit exceeded: ' . $data['message']);
            } elseif (isset($data['response']) && count($data['response']) > 0) {
                return $data['response'];
            }
        }
        
        try {
            // If no cache is available, make a request to the API
            $response = $this->requester->request();
            $data = json_decode($response, true);

            if (isset($data['message']) && strpos($data['message'], 'rate limit') !== false) {
                throw new Exception('Rate limit exceeded: ' . $data['message']);
            } elseif (isset($data['response']) && count($data['response']) > 0) {
                // Store the response data in the cache file for future use
                file_put_contents($filepath, $response);
            }

            return $data['response'] ?? null;
        } catch (Exception $e) {
            // In case of an error during the request, throw an exception
            throw new \Exception('Error fetching data from the API.');
        }
    }
}
