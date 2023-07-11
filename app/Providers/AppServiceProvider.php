<?php

/**
 * The AppServiceProvider class is responsible for bootstrapping the application.
 *
 * The AppServiceProvider is a part of the application's service container and
 * contains a 'boot' method. This method is called after all other service
 * providers have been registered, meaning you have access to all other services
 * that have been registered by the framework.
 *
 * In the 'boot' method, an instance of Dotenv is created as "immutable", meaning
 * once an environment variable is set, it can't be overridden. This instance is
 * pointed to the path where .env file can be found. Then, the 'load' method loads
 * the environment variables from the .env file, making them available globally
 * via getenv(), $_ENV, or $_SERVER. Finally, the default timezone used by all
 * date/time functions in the script is set to 'UTC'.
 *
 * PHP version 8.1
 *
 * @category   NBADataFiltering
 * @package    NBA_API
 * @author     Harry Ji <jiharry@hotmail.com>
 * @license    https://www.opensource.org/licenses/mit-license.html  MIT License
 * @link       https://github.com/harryji168/NBA-API
 */

namespace App\Providers;

use Config\Database;
use Migrations\MigrationManager;
use Dotenv\Dotenv;

class AppServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * Load environment variables from .env file and set the default timezone.
     */
    public function boot()
    {

        // Dotenv::createImmutable creates an instance of Dotenv that's "immutable". This means
        // once an environment variable is set, it can't be overridden. The argument to this method
        // is the path where .env file can be found.
        $dotenv = Dotenv::createImmutable(__DIR__. '/../../');

        // load() method will load the environment variables from the .env file. These environment
        // variables will now be available globally via getenv(), $_ENV or $_SERVER.
        $dotenv->load();

        // The date_default_timezone_set function sets the default timezone used by all date/time
        // functions in the script. 'UTC' is Coordinated Universal Time.
        date_default_timezone_set('UTC');

        // Disable all error reporting, including errors, warnings, and notices for better user experience
        error_reporting(0);
        
        // Instantiate the Database class and establish a connection.
        // The getConnection() method is presumably defined in the Database class
        // and returns a database connection object.
        $conn = (new Database())->getConnection();

        // Conditionally run migrations or rollbacks
        if ($_ENV['RUN_MIGRATIONS'] === 'true') {
            // Create MigrationManager instance
            $manager = new MigrationManager($conn);

            // Call the applyMigrations() method on the MigrationManager instance.
            // This method applies all unapplied migrations in the 'migrations' directory.
            $manager->applyMigrations();
            die("Migrations completed");
        } elseif ($_ENV['RUN_MIGRATIONS'] === 'rollback') {
            // Create MigrationManager instance
            $manager = new MigrationManager($conn);

            // Call the rollbackMigrations() method on the MigrationManager instance.
            // This method rolls back all applied migrations.
            $manager->rollbackMigrations();
            die("Rollback completed");
        }
    }
}
