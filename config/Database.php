<?php

/**
 * The Database class is responsible for setting up and retrieving the database connection.
 *
 * An instance of the Database class can be created and the getConnection method called to establish
 * a connection with the database using the details from the .env file. If the connection is successful,
 * it is stored in the $conn property. If the connection fails, an exception is caught
 * and the error message is displayed.
 *
 * PHP version 8.1
 *
 * @category   NBADataFiltering
 * @package    NBA_API
 * @author     Harry Ji <jiharry@hotmail.com>
 * @license    https://www.opensource.org/licenses/mit-license.html  MIT License
 * @link       https://github.com/harryji168/NBA-API
 */

namespace Config;

use PDO;
use PDOException;

class Database
{
    /**
     * The host of the database.
     *
     * @var    string
     */
    private $host;

    /**
     * The name of the database.
     *
     * @var    string
     */
    private $db_name;

    /**
     * The username used to access the database.
     *
     * @var    string
     */
    private $username;

    /**
     * The password used to access the database.
     *
     * @var    string
     */
    private $password;

    /**
     * The connection to the database.
     *
     * @var    PDO
     */
    private $conn;

    public function __construct()
    {
        $this->host = $_ENV['DB_HOST'];
        $this->db_name = $_ENV['DB_NAME'];
        $this->username = $_ENV['DB_USERNAME'];
        $this->password = $_ENV['DB_PASSWORD'];
    }

    /**
     * This method attempts to connect to the database using the details provided.
     * If the connection is successful, it is stored in the $conn property.
     * If the connection fails, an exception is caught and the error message is displayed.
     *
     * @return PDO The connection to the database.
     */
    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
