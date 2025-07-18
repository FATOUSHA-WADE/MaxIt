<?php
require "./../vendor/autoload.php";
require "./../app/core/env.php";
require "./../app/core/Router.php";
loadEnv(__DIR__ . '/../.env');

use App\Core\Router;

Router::resolve();
