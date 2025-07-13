<?php

namespace App\Middlewares;

class PasswordHashMiddleware
{
    public static function handle(array $data): array
    {
        if (isset($data['password']) && isset($data['confirm_password'])) {
            if (!self::verifyPasswordMatch($data['password'], $data['confirm_password'])) {
                throw new \InvalidArgumentException('Les mots de passe ne correspondent pas');
            }
            
            unset($data['confirm_password']);
        }
        
        return self::hashPassword($data);
    }
    

    public static function hashPassword(array $data): array
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        return $data;
    }

    public static function verifyPasswordMatch(string $password, string $confirmPassword): bool
    {
        return $password === $confirmPassword;
    }
}