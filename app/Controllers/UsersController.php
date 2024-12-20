<?php

namespace App\Controllers;

use App\Enums\RolesEnum;
use App\Models\User;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\Authentication\Auth;

use function array_map;
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
                echo json_encode(['error' => 'Erro ao salvar user']);
            }
        } else {
            echo json_encode(['error' => 'Erro ao criar user']);
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

    public function professors(): void
    {
        $professors = User::where(['role_id' => RolesEnum::PROFESSOR->value]);

        $professorsArray = array_map(function ($professor) {
            return [
            'id' => $professor->id,
            'name' => $professor->name,
            ];
        }, $professors);
        echo json_encode(['data' => $professorsArray]);
    }
}
