<?php

namespace App\Models;

use App\Core\Model;
use PDOException;

class Users extends Model
{
    protected string $table = 'users';

    public function getSchoolAdmins(): array
    {
        try {
            $sql = "SELECT users.id, users.school_id, users.username, users.created_at, schools.name AS school_name
                    FROM {$this->table} AS users
                    INNER JOIN schools ON schools.id = users.school_id
                    WHERE users.role = 'school'
                    ORDER BY users.created_at DESC";
            return $this->db->query($sql)->fetchAll();
        } catch (PDOException $e) {
            error_log("Database Error [{$this->table}::getSchoolAdmins]: " . $e->getMessage());
            throw $e;
        }
    }

    public function usernameExists(string $username, ?int $exceptId = null): bool
    {
        try {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE username = ?";
            $params = [$username];
            if ($exceptId !== null) {
                $sql .= " AND id != ?";
                $params[] = $exceptId;
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return (int) $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Database Error [{$this->table}::usernameExists]: " . $e->getMessage());
            throw $e;
        }
    }

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
