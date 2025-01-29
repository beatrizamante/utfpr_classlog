<?php

namespace App\Middleware;

use Core\Exceptions\HTTPException;
use Core\Http\Middleware\Middleware;
use Core\Http\Request;
use Lib\Authentication\Auth;

class AdminRole implements Middleware
{
    public function handle(Request $request): void
    {
        if (Auth::user()->role_id != 1) {
            header('Content-Type: application/json', true, 401);
            echo json_encode(['error' => 'Acesso restrito a admnistradores']);
            exit;
        }
    }
}
