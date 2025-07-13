<?php
function loadEnv($path)
{
    if (!file_exists($path)) return;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        if (strpos($line, '=') !== false) {
            list($name, $value) = array_map('trim', explode('=', $line, 2));
        }
        $_ENV[$name] = $value;
        putenv("$name=$value");
    }
}

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__,2));
$dotenv->load();
define('APP_URL', $_ENV['APP_URL']);
define('DB_HOST',$_ENV['DB_HOST']);
define('DB_PORT',$_ENV['DB_PORT']);
define('DB_NAME',$_ENV['DB_NAME']);
define('DB_USER',$_ENV['DB_USER']);
define('DB_PASS',$_ENV['DB_PASS']);
define('TWILIO_SID',$_ENV['TWILIO_SID']);
define('TWILIO_TOKEN',$_ENV['TWILIO_TOKEN']);
define('TWILIO_FROM',$_ENV['TWILIO_FROM']);

