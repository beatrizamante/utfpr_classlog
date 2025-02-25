<?php

namespace App\Middleware;

use Core\Exceptions\HTTPException;
use Core\Http\Middleware\Middleware;
use Core\Http\Request;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Lib\Authentication\Auth;
use function dd;
use function glob;

class Authenticate implements Middleware
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

    if (!$data) {
      http_response_code(401);
      echo json_encode(["error" => "Token inválido"]);
      exit();
    }

//    echo json_encode(["message" => "Autorizado", "user" => $data]);
  }

  public function validatesToken($token) {
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
