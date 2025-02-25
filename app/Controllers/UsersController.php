<?php

namespace App\Controllers;

use App\Enums\RolesEnum;
use App\Models\User;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Firebase\JWT\JWT;
use Lib\Authentication\Auth;

use function array_map;
use function getenv;
use function hash;
use function json_encode;
use function password_hash;

class UsersController extends Controller
{
    public function register(Request $request): void
    {
        $params = $request->getBody();
        $params['role_id'] = RolesEnum::PROFESSOR->value;
        $user = new User($params);

        if ($user->isValid()) {
            if ($user->save()) {
                echo json_encode(['success' => 'Usuário criado com sucesso']);
            } else {
                echo json_encode(['error' => $user->getErrors()]);
            }
        } else {
            echo json_encode(['error' => $user->getErrors()]);
        }
    }

    public function login(Request $request): void
    {
        $params = $request->getBody();
        $user = User::findByUniversityRegistry($params['university_registry']);
        if ($user && $user->authenticate($params['password'])) {
            Auth::login($user);

            $payload = [
            "iss" => "http://localhost",
            "aud" => "http://localhost",
            "iat" => time(),
            "exp" => time() + (60 * 60),
            "user_id" => $user->id
            ];
            $token = JWT::encode($payload, $_ENV['PASSWORD_KEY_HASH'], 'HS256');
            echo json_encode([
              'success' => 'Logado com sucesso',
              'role' => $user->roleName(),
              'token' => $token
            ]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Credenciais erradas']);
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
          /** @var \App\Models\Subject[] $subjects */

            $subjects = $professor->subjects;
            return [
            'id' => $professor->id,
            'name' => $professor->name,
              'maetérias' => array_map(function ($subject) {
                return [$subject->name];
              }, $subjects)
            ];
        }, $professors);
        echo json_encode(['data' => $professorsArray]);
    }
}
