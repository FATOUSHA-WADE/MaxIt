<?php

namespace App\Core;

class Session
{
    private static ?Session $instance = null;
    
    private function __construct()
    {
        $this->startSessionSafely();
    }
    
    private function startSessionSafely(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            try {
                session_start();
            } catch (\Exception $e) {
                error_log("Erreur de session: " . $e->getMessage());
                
                if (ini_get("session.use_cookies")) {
                    $params = session_get_cookie_params();
                    setcookie(session_name(), '', time() - 42000,
                        $params["path"], $params["domain"],
                        $params["secure"], $params["httponly"]
                    );
                }
                
                @session_destroy();
                
                session_start();
            }
        }
    }
    
    public static function getInstance(): Session
    {
        if (self::$instance === null) {
            self::$instance = new Session();
        }
        return self::$instance;
    }
    
    public function isLoggedIn(): bool
    {
        return isset($_SESSION['user']);
    }
    
    public function getUserType(): ?string
    {
        return $this->isLoggedIn() ? $_SESSION['user']['type'] : null;
    }
    
    public function getUser()
    {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        try {
            return $_SESSION['user'];
        } catch (\Throwable $e) {
            error_log("Erreur lors de la récupération de l'utilisateur en session: " . $e->getMessage());
            unset($_SESSION['user']);
            return null;
        }
    }
    
    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }
    
    public function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }
    
    public function delete(string $key): void
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    

    public function destroy(): void
    {
        $_SESSION = [];
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }
    
    public function regenerateId(bool $deleteOldSession = true): bool
    {
        return session_regenerate_id($deleteOldSession);
    }

    public function remove(string $key): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    

    public function create($personne): void
    {
        $this->set('user', $personne);
        $this->set('user_id', $personne->getTelephone());
        $this->set('user_type', $personne->getTypePersonne());
        $this->set('logged_in', true);
        
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }
}