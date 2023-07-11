<?php

namespace App\Database\Migrations;

class CreateArenasTable
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function up()
    {
        $query = "CREATE TABLE arenas (
                    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(255) NOT NULL,
                    city VARCHAR(255) NOT NULL,
                    state VARCHAR(255) 
                  )";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }

    public function down()
    {
        $query = "DROP TABLE arenas";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }
}
