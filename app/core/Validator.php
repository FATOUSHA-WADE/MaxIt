<?php
namespace App\Core;

class Validator {

    private static array $errors = [];
    private static array $validationRules = [];

    public static function init()
    {
        self::$validationRules = [
            'required' => fn($value, $message = null) => 
                (trim($value) !== '') || self::addError('required', $message ?? 'Ce champ est obligatoire.'),
                
            'telephone' => fn($value, $message = null) => 
                preg_match('/^(77|78|75|76|70)+[0-9]{7}$/', $value) || self::addError('telephone', $message ?? 'Numéro de téléphone invalide. Formats acceptés: 77XXXXXXX, 78XXXXXXX, 75XXXXXXX, 76XXXXXXX, 70XXXXXXX'),
                
            'cni' => fn($value, $message = null) => 
                preg_match('/^[12][0-9]{12}$/', $value) || self::addError('cni', $message ?? 'Numéro de carte d\'identité invalide. Format accepté: 1XXXXXXXXXXXX ou 2XXXXXXXXXXXX (13 chiffres)'),
                
            'email' => fn($value, $message = null) => 
                filter_var($value, FILTER_VALIDATE_EMAIL) || self::addError('email', $message ?? 'Adresse email invalide.'),
                
            'minLength' => function($value, $min = 1, $message = null) {
                return strlen(trim($value)) >= $min || self::addError('minLength', $message ?? "Ce champ doit contenir au moins $min caractères.");
            },
                
            'maxLength' => function($value, $max = 255, $message = null) {
                return strlen(trim($value)) <= $max || self::addError('maxLength', $message ?? "Ce champ ne peut pas dépasser $max caractères.");
            },
                
            'numeric' => fn($value, $message = null) => 
                is_numeric($value) || self::addError('numeric', $message ?? 'Ce champ doit être un nombre.'),
                
            'alphanumeric' => fn($value, $message = null) => 
                preg_match('/^[a-zA-Z0-9]+$/', $value) || self::addError('alphanumeric', $message ?? 'Ce champ doit être alphanumérique.'),
                
            'password' => fn($value, $message = null) => 
                preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $value) || 
                self::addError('password', $message ?? 'Le mot de passe doit contenir au moins 8 caractères dont une majuscule, une minuscule et un chiffre.'),
                
            'matches' => function($value, $valueToMatch = '', $message = null) {
                return $value === $valueToMatch || self::addError('matches', $message ?? 'Les valeurs ne correspondent pas.');
            },

            'unique' => function($value, $table, $field, $message = null) {
                $db = App::getInstance()->getDependency('db');
                $sql = "SELECT COUNT(*) as count FROM $table WHERE $field = :value";
                $result = $db->query($sql, ['value' => $value]);
                return ($result[0]['count'] === 0) || self::addError('unique', $message ?? "Cette valeur est déjà utilisée.");
            }
        ];
    }

    private function __construct() {}

    public static function getErrors(): array {
        return self::$errors;
    }

    public static function addError(string $field, string $message): bool {
        self::$errors[$field] = $message;
        return false;
    }

    public static function isValid(): bool {
        return empty(self::$errors);
    }
    
    public static function resetErrors(): void {
        self::$errors = [];
    }

    public static function reset(): void
    {
        self::$errors = [];
    }

    public static function validate(string $rule, string $field, $value, array $params = [], ?string $message = null): bool {
        if (empty(self::$validationRules)) {
            self::init();
        }
        
        $rule = (string)$rule;
        
        if (!isset(self::$validationRules[$rule])) {
            self::addError($field, "Règle de validation '$rule' inconnue");
            return false;
        }

        $validationFn = self::$validationRules[$rule];
        
        switch($rule) {
            case 'minLength':
                if (empty($params)) {
                    $params[] = 1;
                }
                break;
            case 'maxLength':
                if (empty($params)) {
                    $params[] = 255;
                }
                break;
            case 'matches':
                if (empty($params)) {
                    $params[] = '';
                }
                break;
        }
        
        if ($message !== null) {
            $params[] = $message;
        }
        
        try {
            $result = $validationFn($value, ...$params);
            return $result !== false;
        } catch (\ArgumentCountError $e) {
            self::addError($field, "Erreur de validation pour le champ '$field'");
            return false;
        } catch (\Exception $e) {
            self::addError($field, "Erreur: " . $e->getMessage());
            return false;
        }
    }

    public static function validateData(array $data, array $rules): bool {
        if (empty(self::$validationRules)) {
            self::init();
        }
        
        self::resetErrors();
        
        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? '';
            
            foreach ($fieldRules as $rule) {
                $ruleName = $rule;
                $params = [];
                $message = null;
                
                if (is_numeric($ruleName)) {

                    $params[] = (int)$ruleName;
                    $ruleName = 'minLength';
                } else if (is_array($rule)) {
                    $ruleName = $rule[0];
                    $params = array_slice($rule, 1);
                    

                    if (is_numeric($ruleName)) {
                        $params = [(int)$ruleName];
                        $ruleName = 'minLength';
                    }
                    

                    if (count($params) > 0 && is_string(end($params)) && strpos(end($params), ':message:') === 0) {
                        $message = substr(array_pop($params), 9);
                    }
                }
                
                if (!self::validate($ruleName, $field, $value, $params, $message)) {
                    break;
                }
            }
        }
        
        return self::isValid();
    }
}

Validator::init();