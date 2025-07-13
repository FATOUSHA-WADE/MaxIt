<?php
namespace App\Core;

use App\Core\Upload;
use App\Core\Session;
use App\Core\Database;
use App\Services\EnvoyerMessage;
use App\Services\SecurityService;
use App\Repository\CompteRepository;
use App\Repository\PersonneRepository;

class App
{
    private static ?\App\Core\App $instance = null;
    private array $dependencies = [];
    private bool $isInitializingDependencies = false;
    
    private function __construct()
    {
        $this->initDependencies();
    }
    
    public static function getInstance(): \App\Core\App
    {
        if (self::$instance === null) {
            self::$instance = new \App\Core\App();
        }
        return self::$instance;
    }
    
    private function initDependencies(): void
    {

        if ($this->isInitializingDependencies) {
            return;
        }
        
        $this->isInitializingDependencies = true;
        
        try {

            try {
                $this->dependencies['db'] = Database::getInstance();
                error_log("Base de données initialisée avec succès");
            } catch (\Exception $e) {
                error_log("Erreur d'initialisation de la base de données: " . $e->getMessage());

            }
            

            $this->dependencies['session'] = Session::getInstance();

            $this->dependencies['envoyerMessage'] = EnvoyerMessage::getInstance();
            

            if (isset($this->dependencies['db'])) {
                try {
                    $this->dependencies['personneRepository'] = new PersonneRepository();
                    $this->dependencies['compteRepository'] = new CompteRepository();
                    $this->dependencies['transactionRepository'] = new \App\Repository\TransactionRepository($this->getDependency('db'));
                } catch (\Exception $e) {
                    error_log("Erreur d'initialisation des repositories: " . $e->getMessage());
                }
            }
            

            if (isset($this->dependencies['personneRepository'])) {
                try {
                    $this->dependencies['security'] = new SecurityService(
                        $this->dependencies['personneRepository'],
                        $this->dependencies['db']
                    );
                } catch (\Exception $e) {
                    error_log("Erreur d'initialisation du service de sécurité: " . $e->getMessage());
                }
            }
            

            $this->dependencies['upload'] = new Upload();
            

            error_log("Dépendances initialisées: " . implode(', ', array_keys($this->dependencies)));
            
        } catch (\Exception $e) {
            error_log("Erreur lors de l'initialisation des dépendances: " . $e->getMessage());
        } finally {
            $this->isInitializingDependencies = false;
        }
    }
    
    public function getDependency(string $name)
    {
        if (!isset($this->dependencies[$name])) {
            throw new \Exception("Dépendance '$name' non trouvée. Dépendances disponibles: " . implode(', ', array_keys($this->dependencies)));
        }
        
        if ($this->dependencies[$name] instanceof \Closure) {
            $this->dependencies[$name] = $this->dependencies[$name]();
        }
        
        return $this->dependencies[$name];
    }
}



