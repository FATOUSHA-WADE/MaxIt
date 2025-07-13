<?php

namespace App\Core;

class Middleware
{
    private static array $ruleCheckers = [
        'userNotLoggedIn' => 'checkUserNotLoggedIn',
        'userTypeNotEqual' => 'checkUserTypeNotEqual'
    ];

    private static array $rules = [
        'auth' => [
            'condition' => 'userNotLoggedIn',
            'redirect' => '/',
            'message' => ''
        ],
        'isCommercial' => [
            'condition' => 'userTypeNotEqual',
            'value' => 'COMMERCIAL',
            'statusCode' => 403,
            'message' => 'Accès interdit : réservé aux commerciaux.'
        ],
        'isClient' => [
            'condition' => 'userTypeNotEqual',
            'value' => 'CLIENT',
            'statusCode' => 403,
            'message' => 'Accès interdit : réservé aux clients.'
        ]
    ];

    public static function handle(array $middlewares)
    {
        $app = App::getInstance();
        $session = $app->getDependency('session');
        
        foreach ($middlewares as $middleware) {
            if ($middleware === 'PasswordHashMiddleware') {
                \App\Middlewares\PasswordHashMiddleware::handle($_POST);
                continue;
            }

            if (isset(self::$rules[$middleware])) {
                self::applyRule($middleware, $session);
            }
        }
    }

    private static function applyRule(string $rule, $session): void
    {
        $ruleConfig = self::$rules[$rule];
        $condition = $ruleConfig['condition'];
        
        if (isset(self::$ruleCheckers[$condition])) {
            $checkMethod = self::$ruleCheckers[$condition];
            self::$checkMethod($ruleConfig, $session);
        }
    }
    
    private static function checkUserNotLoggedIn(array $ruleConfig, $session): void
    {
        if (!$session->isLoggedIn()) {
            header('Location: ' . $ruleConfig['redirect']);
            exit;
        }
    }
    
    private static function checkUserTypeNotEqual(array $ruleConfig, $session): void
    {
        if (!$session->isLoggedIn() ||
            strtoupper($session->getUserType()) !== $ruleConfig['value']) {
            http_response_code($ruleConfig['statusCode']);
            echo $ruleConfig['message'];
            exit;
        }
    }
}