<?php

namespace App\Core;

use PDO;
use PDOException;

abstract class Model
{
    protected PDO $db;
    protected string $table;
    protected ?int $schoolId;

    public function __construct(?int $schoolId = null)
    {
        $this->db = Database::connect();
        $this->schoolId = $schoolId;
    }

    public function all(): array
    {
        try {
            $sql = "SELECT * FROM {$this->table}";
            $params = [];

            if ($this->schoolId !== null) {
                $sql .= " WHERE school_id = ?";
                $params[] = $this->schoolId;
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Database Error [{$this->table}::all]: " . $e->getMessage());
            throw $e;
        }
    }

    public function find(int|string $id): mixed
    {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id = ?";
            $params = [$id];

            if ($this->schoolId !== null) {
                $sql .= " AND school_id = ?";
                $params[] = $this->schoolId;
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            return $stmt->fetch() ?: null;
        } catch (PDOException $e) {
            error_log("Database Error [{$this->table}::find]: " . $e->getMessage());
            throw $e;
        }
    }

    public function create(array $data): bool|string
    {
        try {
            if ($this->schoolId !== null && !array_key_exists('school_id', $data)) {
                $data['school_id'] = $this->schoolId;
            }

            $columns = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_fill(0, count($data), '?'));

            $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";

            $stmt = $this->db->prepare($sql);
            $stmt->execute(array_values($data));

            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Database Error [{$this->table}::create]: " . $e->getMessage());
            throw $e;
        }
    }

    public function update(int|string $id, array $data): bool
    {
        try {
            $fields = [];
            foreach (array_keys($data) as $column) {
                $fields[] = "{$column} = ?";
            }
            $fieldsSql = implode(', ', $fields);

            $sql = "UPDATE {$this->table} SET {$fieldsSql} WHERE id = ?";
            $params = array_values($data);
            $params[] = $id;

            if ($this->schoolId !== null) {
                $sql .= " AND school_id = ?";
                $params[] = $this->schoolId;
            }

            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Database Error [{$this->table}::update]: " . $e->getMessage());
            throw $e;
        }
    }

    public function delete(int|string $id): bool
    {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = ?";
            $params = [$id];

            if ($this->schoolId !== null) {
                $sql .= " AND school_id = ?";
                $params[] = $this->schoolId;
            }

            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Database Error [{$this->table}::delete]: " . $e->getMessage());
            throw $e;
        }
    }

    public function count(array $criteria = []): int
    {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        $params = [];

        if (!empty($criteria)) {
            $clauses = [];
            foreach ($criteria as $column => $value) {
                $clauses[] = "{$column} = ?";
                $params[] = $value;
            }
            $sql .= " WHERE " . implode(" AND ", $clauses);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn();
    }
}
