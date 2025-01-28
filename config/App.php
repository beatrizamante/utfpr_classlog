<?php

namespace Config;

use App\Middleware\AdminRole;

class App
{
    public static array $middlewareAliases = [
        'auth' => \App\Middleware\Authenticate::class,
        'role:admin' => AdminRole::class
    ];
}
