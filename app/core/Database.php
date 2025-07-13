<?php

namespace App\Core;

use PDO;
use PDOException;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__,2));
$dotenv->load();

class Database
{
    private static ?PDO $instance = null;
    private static bool $isConnecting = false;
    
    private function __construct() {}
    
    public static function getInstance(): PDO
    {
        if (self::$isConnecting) {
            error_log("Détection d'une boucle infinie dans l'initialisation de la base de données");
            throw new \RuntimeException("Boucle infinie détectée dans l'initialisation de la base de données");
        }
        
        if (self::$instance === null) {
            self::$isConnecting = true;
            
            try {
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_TIMEOUT => 5,
                    PDO::ATTR_EMULATE_PREPARES => false
                ];
                
                $host = getenv('DB_HOST') ?? 'localhost';
                $port = getenv('DB_PORT') ?? '5432';
                $dbname = getenv('DB_NAME') ?? 'maxit';
                $username = getenv('DB_USER') ?? 'postgres';
                $password = getenv('DB_PASS') ?? 'Marakhib';
                
                if (empty($host) || empty($dbname) || empty($username)) {
                    throw new \RuntimeException("Variables d'environnement de base de données manquantes");
                }
                
                self::$instance = new PDO(
                    "pgsql:host=$host;port=$port;dbname=$dbname",
                    $username,
                    $password,
                    $options
                );
                
                self::$isConnecting = false;
                
                error_log("Base de données PostgreSQL initialisée avec succès (une seule fois)");
            } catch (PDOException $e) {
                self::$isConnecting = false;
                error_log("Erreur de connexion à la base de données PostgreSQL : " . $e->getMessage());
                throw new \RuntimeException("Impossible de se connecter à la base de données : " . $e->getMessage());
            }
        }
        
        return self::$instance;
    }
    
    public static function resetInstance(): void
    {
        self::$instance = null;
        self::$isConnecting = false;
    }
}