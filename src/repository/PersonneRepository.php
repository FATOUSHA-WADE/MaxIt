<?php

namespace App\Repository;
use PDO;
use App\Entity\PersonneEntity;
use App\Abstract\AbstractRepository;

class PersonneRepository extends AbstractRepository
{
    public function findByLogin(string $login)
    {
        try {
            $sql = "SELECT * FROM personne WHERE login = :login";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['login' => $login]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erreur dans findByLogin : " . $e->getMessage());
            return null;
        }
    }

    public function findByTelephone(string $telephone): ?PersonneEntity
    {
        $sql = "SELECT * FROM personne WHERE telephone = :telephone";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['telephone' => $telephone]);
        $data = $stmt->fetch();
        return $data ? PersonneEntity::toObject($data) : null;
    }

    public function findByNumeroIdentite(string $numeroIdentite): ?PersonneEntity
    {
        $sql = "SELECT * FROM personne WHERE numero_identite = :numeroIdentite";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['numeroIdentite' => $numeroIdentite]);
        $data = $stmt->fetch();
        return $data ? PersonneEntity::toObject($data) : null;
    }

    public function create(array $data)
    {
        try {
        
            $logData = array_diff_key($data, ['password' => '']);
            error_log("Tentative d'insertion personne avec données: " . json_encode($logData));
            
            $columns = implode(', ', array_map(fn($key) => "\"$key\"", array_keys($data)));
            $placeholders = implode(', ', array_map(fn($key) => ":$key", array_keys($data)));
            
            $sql = "INSERT INTO personne ($columns) VALUES ($placeholders) RETURNING \"telephone\"";
            error_log("SQL: $sql");
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($data);
            
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            error_log("Résultat de l'insertion personne: " . json_encode($result));
            
            return $result['telephone'] ?? null;
        } catch (\PDOException $e) {
            error_log("Erreur SQL lors de la création d'une personne : " . $e->getMessage() . " - Code : " . $e->getCode());
            return null;
        }
    }
}
