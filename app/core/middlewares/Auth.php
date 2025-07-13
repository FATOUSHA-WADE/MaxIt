<?php
namespace App\Middlewares;

use App\Core\Session;


class Auth
{
    public function __invoke()
    {
        Session::getInstance();
        if (empty(Session->get('user'))) {
            header('Location: /login');
            exit;
        }
    }
}


