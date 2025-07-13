<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/core/env.php';
require_once __DIR__ . '/../app/core/Router.php';
loadEnv(__DIR__ . '/../.env');

use App\Core\Router;

Router::resolve();
