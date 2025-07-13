<?php


namespace App\Repository;

use App\Entity\CompteEntity;
use App\Abstract\AbstractRepository;

class CompteRepository extends AbstractRepository
{
    /**
     * Récupère les comptes associés à une personne
     * 
     * @param string $personneId ID (téléphone) de la personne
     * @return array Liste des comptes associés à la personne
     */
    public function findByPersonne($personneId): array
    {
        try {
            // S'assurer que personneId est bien une chaîne de caractères
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
}