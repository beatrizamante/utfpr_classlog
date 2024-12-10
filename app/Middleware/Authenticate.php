<?php

namespace App\Middleware;

use Core\Exceptions\HTTPException;
use Core\Http\Middleware\Middleware;
use Core\Http\Request;
use Lib\Authentication\Auth;

class Authenticate implements Middleware
{
    public function handle(Request $request): void
    {
        if (!Auth::check()) {
           
            header('Content-Type: application/json', true, 401);
            echo json_encode(['error' => 'Você precisa estar autenticado para acessar esta página.']);
            exit;
        }
    }
}
