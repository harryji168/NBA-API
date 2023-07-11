<?php

namespace App\Database\Migrations;

class CreateLinescoresTable
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function up()
    {
        // This is the migration
        $query = "CREATE TABLE linescores (
                    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    game_id INT(6) UNSIGNED NOT NULL,
                    team_id INT(6) UNSIGNED NOT NULL,
                    quarter INT(2) UNSIGNED NOT NULL,
                    points INT(6) UNSIGNED,
                    FOREIGN KEY (game_id) REFERENCES games(id),
                    FOREIGN KEY (team_id) REFERENCES teams(id)
                  )";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }

    public function down()
    {
        // This is the reversal of the migration
        $query = "DROP TABLE linescores";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }
}
