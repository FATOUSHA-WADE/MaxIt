<?php

namespace App\Abstract;

use PDO;
use PDOException;
use App\Core\Database;

abstract class AbstractRepository
{
    protected PDO $pdo;
    private static bool $isInitializing = false;
    
    public function __construct()
    {

        if (self::$isInitializing) {
            error_log("Boucle d'initialisation détectée dans AbstractRepository");
            throw new \RuntimeException("Boucle d'initialisation détectée");
        }
        
        self::$isInitializing = true;
        
        try {
            $this->pdo = Database::getInstance();
            self::$isInitializing = false;
        } catch (\Exception $e) {
            self::$isInitializing = false;
            error_log("Erreur lors de l'initialisation du repository : " . $e->getMessage());
            throw new \RuntimeException("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
        }
    }

    public function findAll($table): array
    {
        try {
            $sql = "SELECT * FROM {$table}";
            $cursor = $this->pdo->prepare($sql);
            $cursor->execute();

            return $cursor->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur dans findAll : " . $e->getMessage());
            return [];
        }
    }

    public function findAllClause($clause, $table): array
    {
        try {
            if (!$clause || empty($clause)) {
                return [];
            }

            $where = implode(' AND ', $clause);

            $sql = "SELECT * FROM {$table} WHERE {$where}";

            $cursor = $this->pdo->prepare($sql);
            $cursor->execute();

            return $cursor->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur dans findAllClause : " . $e->getMessage());
            return [];
        }
    }

    protected function insertOne(string $table, array $data): string
    {
        $columns = implode(', ', array_map(fn($key) => "\"$key\"", array_keys($data)));
        $donnees = implode(',', array_map(fn($key) => ":$key", array_keys($data)));

        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$donnees}) RETURNING telephone";
        error_log("SQL: " . $sql);

        try {
            $cursor = $this->pdo->prepare($sql);
            $cursor->execute($data);
            
            $result = $cursor->fetch(PDO::FETCH_ASSOC);
            return $result['telephone'] ?? '';
        } catch (PDOException $e) {
            error_log("Erreur SQL : " . $e->getMessage());
            throw $e;
        }
    }
    
    public function findById(string $table, string $telephone): ?array
    {
        try {
            $sql = "SELECT * FROM {$table} WHERE telephone = :telephone";
            $cursor = $this->pdo->prepare($sql);
            $cursor->execute(['telephone' => $telephone]);
            $result = $cursor->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (PDOException $e) {
            error_log("Erreur dans findById : " . $e->getMessage());
            return null;
        }
    }
    
    public function getTableColumns(string $table): array
    {
        try {
            $sql = "SELECT column_name, data_type 
                    FROM information_schema.columns 
                    WHERE table_name = :table 
                    ORDER BY ordinal_position";
            $cursor = $this->pdo->prepare($sql);
            $cursor->execute(['table' => $table]);

            return $cursor->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur dans getTableColumns : " . $e->getMessage());
            return [];
        }
    }
}


