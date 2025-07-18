<?php


namespace App\Repository;

use App\Entity\CompteEntity;
use App\Abstract\AbstractRepository;
use App\Config\Core\Database;

class CompteRepository extends AbstractRepository
{
  
    public function findByPersonne($personneId): array
    {
        try {
            $personneId = (string) $personneId;
            
            error_log("Recherche des comptes pour la personne avec ID/téléphone: " . $personneId);
            
            $sql = 'SELECT * FROM compte WHERE "personne_telephone" = :personneId';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['personneId' => $personneId]);
            
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            error_log("Nombre de comptes trouvés: " . count($results));
            
            return $results;
        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération des comptes : " . $e->getMessage());
            return [];
        }
    }
    
    public function create(array $data)
    {
        try {
            error_log("Tentative de création d'un compte: " . json_encode($data));
            
            $columns = implode(', ', array_map(fn($key) => "\"$key\"", array_keys($data)));
            $placeholders = implode(', ', array_map(fn($key) => ":$key", array_keys($data)));
            
            $sql = "INSERT INTO compte ($columns) VALUES ($placeholders) RETURNING \"telephone\"";
            error_log("SQL: $sql");
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($data);
            
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            error_log("Résultat de la création du compte: " . json_encode($result));
            
            return $result['telephone'] ?? null;
        } catch (\PDOException $e) {
            error_log("Erreur lors de la création d'un compte : " . $e->getMessage());
            return null;
        }
    }

    public function update(CompteEntity $compte): bool
    {
        try {
            $data = [
                'telephone' => $compte->getTelephone(),
                'solde' => $compte->getSolde(),
                'personne_telephone' => $compte->getPersonneTelephone(),
                'typecompte' => $compte->getTypeCompte()
            ];
            
            $setClause = implode(', ', array_map(fn($key) => "\"$key\" = :$key", array_keys($data)));
            $sql = "UPDATE compte SET $setClause WHERE \"telephone\" = :telephone";
            
            error_log("SQL de mise à jour du compte: $sql");
            
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($data);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la mise à jour du compte : " . $e->getMessage());
            return false;
        }
    }


}