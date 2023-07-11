<?php

namespace App\Database\Migrations;

class CreateGamesTable
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function up()
    {
        $query = "CREATE TABLE games (
                    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    game_id INT(6) NOT NULL,
                    season INT(4) NOT NULL,
                    date_start DATETIME NOT NULL,
                    home_team_id INT(6) UNSIGNED NOT NULL,
                    visitors_team_id INT(6) UNSIGNED NOT NULL,
                    visitors_team_points INT(6) UNSIGNED,
                    home_team_points INT(6) UNSIGNED,
                    arena_id INT(6) UNSIGNED NOT NULL,
                    status VARCHAR(255) NOT NULL,
                    FOREIGN KEY (home_team_id) REFERENCES teams(id),
                    FOREIGN KEY (visitors_team_id) REFERENCES teams(id),
                    FOREIGN KEY (arena_id) REFERENCES arenas(id)
                  )";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }

    public function down()
    {
        $query = "DROP TABLE games";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }
}
