<?php

namespace App\Controllers;

use App\Enums\RolesEnum;
use App\Models\User;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\Authentication\Auth;

use function array_push;
use function json_encode;

class UsersController extends Controller
{
    public function register(Request $request): void
    {

        $params = $request->getBody();
        $params['role_id'] = RolesEnum::PROFESSOR->value;
        $user = new User($params);

        if ($user->isValid()) {
            if ($user->save()) {
                echo json_encode(['success' => 'Criado com sucesso']);
            } else {
                echo json_encode(['error' => 'Você precisa estar autenticado para acessar esta página.']);
            }
        } else {
            echo json_encode(['error' => 'Você precisa estar autenticado para acessar esta página.']);
        }
    }

    public function login(Request $request): void
    {
        $params = $request->getBody();
        $user = User::findByUniversityRegistry($params['university_registry']);
        if ($user && $user->authenticate($params['password'])) {
            Auth::login($user);
            echo json_encode(['success' => 'Logado com sucesso']);
        } else {
            echo json_encode(['error' => 'RA ou senha errados']);
        }
    }

    public function destroy(): void
    {
          Auth::logout();
          echo json_encode(['success' => 'Logout feito com sucesso']);
    }
}
