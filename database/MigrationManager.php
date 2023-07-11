<?php

/**
 * The MigrationManager class is responsible for applying pending migrations.
 *
 * An instance of the MigrationManager class can be created with a database connection
 * provided to the constructor. The applyMigrations method scans a directory for
 * migration files, and applies any migrations that have not yet been marked as
 * applied. Once a migration is applied, it is marked as such in the database.
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

class MigrationManager
{
    /**
     * The database connection.
     *
     * @var PDO
     */
    private $conn;

    /**
     * An instance of the Migration class for managing the migrations table.
     *
     * @var Migration
     */
    private $migrationsTable;

    /**
     * Creates a new MigrationManager instance.
     *
     * @param PDO $db The database connection.
     */
    public function __construct($db)
    {
        $this->conn = $db;
        $this->migrationsTable = new Migration($db);
        $this->migrationsTable->initialize();
    }

    /**
     * Applies any migrations that have not yet been marked as applied.
     *
     * @return void
     */
    public function applyMigrations()
    {
        $migrationsPath = __DIR__. '/migrations/';
        $migrations = glob($migrationsPath . '*.php');
    
        // Sort files by name.
        usort($migrations, function ($a, $b) {
            return strcmp($a, $b);
        });
    
        // Iterate over each migration file
        foreach ($migrations as $file) {
            // Get the filename without extension
            $fileName = pathinfo($file, PATHINFO_FILENAME);
    
            // Check if the migration has already been applied
            if (!$this->migrationsTable->hasBeenApplied($fileName)) {
                // If not, require the migration file
                require_once $file;
    
                // Get the content of the migration file
                $fileContent = file_get_contents($file);
    
                // Use regex to find the class name in the migration file content
                preg_match('/class\s+(\w+)\s+/', $fileContent, $matches);
                $className = $matches[1];
    
                // Instantiate the migration class
                $migration = new $className($this->conn);
    
                // Apply the migration by calling the up() method
                $migration->up();
    
                // Mark the migration as applied in the migrations table
                $this->migrationsTable->markAsApplied($fileName);
            }
        }
    }

    /**
     * Reverses any migrations that have been applied.
     *
     * @return void
     */
    public function rollbackMigrations()
    {
        $migrationsPath = __DIR__. '/migrations/';
        $migrations = glob($migrationsPath . '*.php');
    
        // Sort files by name in reverse order, so the latest migration is rolled back first
        usort($migrations, function ($a, $b) {
            return strcmp($b, $a);
        });
    
        // Iterate over each migration file
        foreach ($migrations as $file) {
            // Get the filename without extension
            $fileName = pathinfo($file, PATHINFO_FILENAME);
    
            // Check if the migration has been applied
            if ($this->migrationsTable->hasBeenApplied($fileName)) {
                // If so, require the migration file
                require_once $file;
    
                // Get the content of the migration file
                $fileContent = file_get_contents($file);
    
                // Use regex to find the class name in the migration file content
                preg_match('/class\s+(\w+)\s+/', $fileContent, $matches);
                $className = $matches[1];
    
                // Instantiate the migration class
                $migration = new $className($this->conn);
    
                // Roll back the migration by calling the down() method
                $migration->down();
    
                // Unmark the migration as applied in the migrations table
                $this->migrationsTable->unmarkAsApplied($fileName);
            }
        }
    }
}
