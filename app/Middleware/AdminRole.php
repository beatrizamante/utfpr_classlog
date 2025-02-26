<?php

namespace App\Middleware;

use App\Enums\RolesEnum;
use App\Models\User;
use Core\Exceptions\HTTPException;
use Core\Http\Middleware\Middleware;
use Core\Http\Request;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Lib\Authentication\Auth;

use function dd;
use function getenv;
use function http_response_code;
use function json_encode;
use function str_replace;

class AdminRole implements Middleware
{
    public function handle(Request $request): void
    {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(["error" => "Token não fornecido"]);
            exit();
        }

        $token = str_replace('Bearer ', '', $headers['Authorization']);
        $data = $this->validatesToken($token);
        $user = User::findById($data['user_id']);

        if ($user->role_id != RolesEnum::ADMIN->value) {
            header('Content-Type: application/json', true, 401);
            echo json_encode(['error' => 'Acesso restrito a admnistradores']);
            exit;
        }
    }

  /**
   *
   * @param string $token
   * @return array<string, mixed>|null
   */

    public function validatesToken(string $token): ?array
    {
        $key = $_ENV['PASSWORD_KEY_HASH'] ?? getenv('PASSWORD_KEY_HASH');

        if (!$key) {
            return null;
        }

        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            return (array) $decoded;
        } catch (Exception $e) {
            return null;
        }
    }
}
