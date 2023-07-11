<?php

/**
 * The Migration class is responsible for handling database migrations.
 *
 * An instance of the Migration class can be created with a database connection
 * provided to the constructor. The initialize method will check if a migrations
 * table exists and create it if it doesn't. The hasBeenApplied method checks if
 * a specific migration has already been applied, and the markAsApplied method
 * records a migration as having been applied.
 *
 * PHP version 8.1
 *
 * @category   NBADataFiltering
 * @package    NBA_API
 * @author     Harry Ji <jiharry@hotmail.com>
 * @license    https://www.opensource.org/licenses/mit-license.html  MIT License
 * @link       https://github.com/harryji168/NBA-API
 */

namespace Migrations;

use PDO;

class Migration
{
    /**
     * The database connection.
     *
     * @var PDO
     */
    private $conn;

    /**
     * The name of the migrations table.
     *
     * @var string
     */
    private $table_name = "migrations";

    /**
     * Creates a new Migration instance.
     *
     * @param PDO $db The database connection.
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Checks if the migrations table exists and creates it if not.
     *
     * @return void
     */
    public function initialize()
    {
        $query = "CREATE TABLE IF NOT EXISTS " . $this->table_name . " (
                    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    migration VARCHAR(255) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                  )";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }

    /**
     * Checks if a specific migration has been applied.
     *
     * @param string $migration The name of the migration.
     * @return bool True if the migration has been applied, false otherwise.
     */
    public function hasBeenApplied($migration)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE migration = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$migration]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Records a migration as having been applied.
     *
     * @param string $migration The name of the migration.
     * @return void
     */
    public function markAsApplied($migration)
    {
        $query = "INSERT INTO " . $this->table_name . " SET migration=:migration";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":migration", $migration);
        $stmt->execute();
    }

    /**
     * Unmarks a migration as applied in the migrations table.
     *
     * @param string $migration The name of the migration to unmark.
     * @return void
     */
    public function unmarkAsApplied($migration)
    {
        $query = "DELETE FROM migrations WHERE migration = :migration";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':migration', $migration);
        $stmt->execute();
    }
}
