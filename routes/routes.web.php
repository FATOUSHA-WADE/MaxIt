<?php
use App\Controllers\AcceuilController;
use App\Controllers\SecurityController;

return $routes = [
    '/' => [
        'controller' => SecurityController::class,
        'action' => 'index',
        'method' => 'GET'
    ],
    '/login' => [
        'controller' => SecurityController::class,
        'action' => 'index',
        'method' => 'GET'
    ],
    '/login' => [
        'controller' => SecurityController::class,
        'action' => 'login',
        'method' => 'POST'
    ],
    '/logout' => [
        'controller' => SecurityController::class,
        'action' => 'logout',
        'method' => 'GET'
    ],
    '/accueil' => [
        'controller' => AcceuilController::class,
        'middlewares' => ['auth'],
        'action' => 'index',
        'method' => 'GET'
    ],
    '/inscription' => [
        'controller' => SecurityController::class,
        'action' => 'create',
        'method' => 'GET'
    ],
    '/inscription' => [
        'controller' => SecurityController::class,
        'action' => 'store',
        'method' => 'POST',
        'middlewares' => ['PasswordHashMiddleware']
    ]
];