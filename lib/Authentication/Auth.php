<?php

namespace Lib\Authentication;

use App\Models\User;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use function dd;
use function getallheaders;
use function getenv;
use function http_response_code;
use function json_encode;
use function str_replace;

class Auth
{
    public static function login($user): void
    {
        $_SESSION['user']['id'] = $user->id;
    }

    public static function user(): ?User
    {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            return null;
            exit();
        }

        $token = str_replace('Bearer ', '', $headers['Authorization']);
        $data = self::validatesToken($token);
        if (isset($data['user_id'])) {
            return User::findById($data['user_id']);
        }
        return null;
    }

    public static function validatesToken($token)
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

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function logout(): void
    {
        unset($_SESSION['user']['id']);
    }
}
