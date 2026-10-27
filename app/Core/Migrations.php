<?php

namespace App\Core;

use PDO;

class Migrations
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->runMigrations();
    }

    private function runMigrations(): void
    {
        $queries = [
            "CREATE TABLE IF NOT EXISTS schools (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                address VARCHAR(255) NOT NULL,
                logo_path VARCHAR(255) DEFAULT NULL,
                status ENUM('active','inactive') DEFAULT 'active',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB;",

            "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                school_id INT DEFAULT NULL,
                username VARCHAR(100) NOT NULL UNIQUE,
                role ENUM('admin','school','teacher','student') NOT NULL,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (school_id) REFERENCES schools(id) ON DELETE CASCADE
            ) ENGINE=InnoDB;"
        ];

        foreach ($queries as $sql) {
            $this->db->exec($sql);
        }
    }
}
