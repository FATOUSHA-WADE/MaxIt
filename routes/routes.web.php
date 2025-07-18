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
        'method' => 'GET',
        'middlewares' => ['auth']

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
         'middlewares' => ['auth'],
        'action' => 'create',
        'method' => 'GET',
        'middlewares' => ['PasswordHashMiddleware']

    ],
    '/inscription' => [
        'controller' => SecurityController::class,
        'action' => 'store',
        'method' => 'POST',
        'middlewares' => ['auth']

    ],
    '/ajouterCompteSecond' => [
        'controller' => SecurityController::class,
        'action' => 'update',
        'method' => 'GET',
        'middlewares' => ['auth']
    ],
    '/ajouterCompteSecond' => [
        'controller' => SecurityController::class,
        'action' => 'update',
        'method' => 'POST',
        'middlewares' => ['auth']
    ]
];