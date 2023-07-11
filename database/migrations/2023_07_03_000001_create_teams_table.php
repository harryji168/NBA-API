<?php

namespace App\Database\Migrations;

class CreateTeamsTable
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function up()
    {
        $query = "CREATE TABLE teams (
                    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(255) NOT NULL,
                    logo VARCHAR(255)
                  )";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }

    public function down()
    {
        $query = "DROP TABLE teams";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }
}
