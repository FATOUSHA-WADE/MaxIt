<?php
namespace App\Services;

use App\Core\App;
use App\Core\Database;
use App\Entity\PersonneEntity;
use App\Repository\PersonneRepository;

class SecurityService
{
    private PersonneRepository $personneRepository;
    private \PDO $db;
    private static bool $isInitializing = false;
    private $app;
    
    public function __construct()
    {
        if (self::$isInitializing) {
            error_log("Boucle d'initialisation détectée dans SecurityService");
            throw new \RuntimeException("Boucle d'initialisation détectée");
        }
        
        self::$isInitializing = true;
        
        try {
            $app = App::getInstance();
            $this->personneRepository = $app->getDependency('personneRepository');
            $this->db = $app->getDependency('db');
            $this->app = App::getInstance();
            self::$isInitializing = false;
        } catch (\Exception $e) {
            self::$isInitializing = false;
            error_log('Erreur dans le constructeur de SecurityService: ' . $e->getMessage());
            throw new \RuntimeException('Impossible d\'initialiser le service de sécurité: ' . $e->getMessage());
        }
    }

    public function inscrire(array $data, $app = null)
    {

        if ($app === null) {
            $app = \App\Core\App::getInstance();
        }
        
        try {

            if ($this->db->inTransaction()) {
                error_log("Transaction déjà active dans SecurityService::inscrire, annulation");
                $this->db->rollBack();
            }
            
            $this->db->beginTransaction();
            error_log("Transaction démarrée dans SecurityService::inscrire");
            
            $personneTelephone = $this->personneRepository->create($data);
            
            if (!$personneTelephone) {
                error_log("Échec de la création de la personne");
                $this->db->rollBack();
                return false;
            }
            
            error_log("Personne créée avec téléphone: $personneTelephone");
            
            try {
                $types = ['principal', 'secondaire'];
                
                try {
                    $stmt = $this->db->query("SELECT unnest(enum_range(NULL::type_compte)) AS type");
                    $availableTypes = $stmt->fetchAll(\PDO::FETCH_COLUMN);
                    if (!empty($availableTypes)) {
                        $types = $availableTypes;
                    }
                } catch (\PDOException $e) {
                    error_log("Impossible de récupérer les types de compte: " . $e->getMessage());
                }
                
                $compteData = [
                    'telephone' => 'C' . substr($personneTelephone, 1, 19),
                    'solde' => 0, 
                    'personne_telephone' => $personneTelephone,
                    'typecompte' => $types[0]
                ];
                
                $compteRepository = $app->getDependency('compteRepository');
                $compteTelephone = $compteRepository->create($compteData);
                
                if (!$compteTelephone) {
                    error_log("Échec de la création du compte");
                    $this->db->rollBack();
                    return false;
                }
                
                error_log("Compte créé avec succès: $compteTelephone pour la personne: $personneTelephone");
                
                $this->db->commit();
                error_log("Transaction validée dans SecurityService::inscrire");
                return $personneTelephone;
                
            } catch (\Exception $e) {
                error_log("Erreur lors de la création du compte: " . $e->getMessage());
                if ($this->db->inTransaction()) {
                    $this->db->rollBack();
                    error_log("Transaction annulée dans SecurityService::inscrire");
                }
                return false;
            }
        } catch (\Exception $e) {
            error_log("Erreur lors de l'inscription : " . $e->getMessage());
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
                error_log("Transaction annulée dans SecurityService::inscrire");
            }
            return false;
        }
    }

    public function seConnecter(string $login, string $password)
    {
        try {
            $personne = $this->personneRepository->findByLogin($login);
            
            if (!$personne) {
                error_log("Utilisateur non trouvé : $login");
                return null;
            }
            
            if (!password_verify($password, $personne['password'])) {
                error_log("Mot de passe incorrect pour l'utilisateur : $login");
                return null;
            }
            
            return new PersonneEntity($personne);
        } catch (\Exception $e) {
            error_log("Erreur lors de la connexion : " . $e->getMessage());
            return null;
        }
    }
    
    private function generateNumeroCompte(): string
    {
        return 'CPT' . date('YmdHis') . rand(1000, 9999);
    }
}
