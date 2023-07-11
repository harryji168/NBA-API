<?php

/**
 * The NbaApiRequester class is responsible for making HTTP requests to the NBA API.
 *
 * The NbaApiRequester class handles formatting HTTP headers and performing GET requests
 * using cURL. The constructor receives the API endpoint URL and headers, formats them,
 * and initializes a logger. The 'request' method performs a GET request using cURL
 * and returns the response from the API endpoint.
 *
 * PHP version 8.1
 *
 * @category   NBADataRetrieval
 * @package    NBA_API
 * @author     Harry Ji <jiharry@hotmail.com>
 * @license    https://www.opensource.org/licenses/mit-license.html  MIT License
 * @link       https://github.com/harryji168/NBA-API
 */

namespace App\Services;

use App\Helpers\DataFilters;
use App\Helpers\Storage;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class NbaApiRequester
{
    /**
     * URL of the NBA API endpoint.
     *
     * @var    string
     */
    private $url;

    /**
     * Formatted HTTP headers.
     *
     * @var    array
     */
    private $headers;

    /**
     * Logger instance to log requests and responses.
     *
     * @var    \Monolog\Logger
     */
    private $logger;

    /**
     * Constructor.
     *
     * @param string $url The URL of the API endpoint.
     * @param array $headers HTTP headers for the request.
     */
    public function __construct($url, $headers)
    {
        $this->url = $url;
        $this->headers = $this->formatHeaders($headers);

        $logFilePath = Storage::path('logs/nba_api.log');

        // Create a logger instance
        $this->logger = new Logger('NBA_API');
        
        $this->logger->pushHandler(new StreamHandler($logFilePath, Logger::DEBUG));
    }

    /**
     * Formats HTTP headers.
     *
     * @param array $headers HTTP headers for the request.
     * @return array The formatted HTTP headers.
     */
    private function formatHeaders($headers)
    {
        $formattedHeaders = [];

        foreach ($headers as $header => $value) {
            $formattedHeaders[] = "$header: $value";
        }

        return $formattedHeaders;
    }

    /**
     * Performs a GET request using cURL.
     *
     * @return string The response from the API endpoint.
     * @throws Exception If cURL encounters an error.
     */
    public function request()
    {
        $curl = curl_init();
        $response = '';
    
        try {
            curl_setopt_array($curl, [
                CURLOPT_URL => $this->url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => $this->headers,
            ]);
    
            $response = curl_exec($curl);
    
            if ($response === false) {
                throw new \Exception('Curl error: ' . curl_error($curl));
            }
    
            //Log successful requests
            $this->logger->info('Successful request', [
                'url' => $this->url,
                'response' => $response,
            ]);
        } catch (\Exception $e) {
            $this->logger->error('Error in curl request', [
                'url' => $this->url,
                'error' => $e->getMessage(),
            ]);
    
            throw $e;
        } finally {
            curl_close($curl);
        }
    
        return $response;
    }
}
