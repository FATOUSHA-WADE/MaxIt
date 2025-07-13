<?php

namespace App\Core;

use App\Core\App;

class Router
{
    protected static $routes;
    

    public static function loadRoutes()
    {
        if (static::$routes === null) {
            $routesFile = dirname(__DIR__, 2) . '/routes/routes.web.php';
            
            if (file_exists($routesFile)) {
                error_log("Chargement des routes depuis: $routesFile");
                static::$routes = require $routesFile;
            } else {
                error_log("Fichier de routes non trouvé: $routesFile");
                static::$routes = [];
            }
        }
        
        return static::$routes;
    }
    
    public static function resolve()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        
        $uri = parse_url($uri, PHP_URL_PATH);
        
        error_log("Résolution de route: $method $uri");
        
        $routes = static::loadRoutes();
        
        if (isset($routes[$uri])) {
            $route = $routes[$uri];
            $controllerClass = $route['controller'];
            $action = $route['action'];
            
            error_log("Route trouvée: $controllerClass@$action");
            
            if (isset($route['middlewares']) && is_array($route['middlewares'])) {
                foreach ($route['middlewares'] as $middleware) {
                    self::applyMiddleware($middleware);
                }
            }
            
            $controllerInstance = new $controllerClass();
            return $controllerInstance->$action();
        }
        
        error_log("Route non trouvée: $method $uri");
        header('HTTP/1.1 404 Not Found');
        echo '404 Not Found';
    }
    

    protected static function applyMiddleware($middlewareName)
    {
        $app = App::getInstance();
        $session = $app->getDependency('session');
        
        switch ($middlewareName) {
            case 'auth':
                if (!$session->get('logged_in')) {
                    error_log("Middleware auth: Utilisateur non connecté, redirection vers /login");
                    header('Location: /login');
                    exit;
                }
                break;
                
            case 'admin':
                if (!$session->get('logged_in') || $session->get('user_type') !== 'admin') {
                    error_log("Middleware admin: Accès non autorisé, redirection vers /");
                    header('Location: /');
                    exit;
                }
                break;
                
            case 'PasswordHashMiddleware':
                error_log("Application du middleware PasswordHashMiddleware");
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['password'])) {
                    $_POST = \App\Middlewares\PasswordHashMiddleware::handle($_POST);
                    error_log("Mot de passe traité par le middleware");
                }
                break;
                
        }
    }
}