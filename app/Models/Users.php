<?php

namespace App\Models;

use App\Core\Model;
use PDOException;

class Users extends Model
{
    protected string $table = 'users';

    // Login helper
    public function getUserByUsername(string $username): ?array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE username = ?");
            $stmt->execute([$username]);
            return $stmt->fetch() ?: null;
        } catch (PDOException $e) {
            error_log("Database Error [{$this->table}::getUserByUsername]: " . $e->getMessage());
            return null;
        }
    }
}
