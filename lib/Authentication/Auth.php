<?php

namespace Lib\Authentication;

use App\Models\User;

use function str_replace;

class Auth
{
    public static function login($user): void
    {
        $_SESSION['user']['id'] = $user->id;
    }

    public static function user(): ?User
    {
        if (isset($_SESSION['user']['id'])) {
            $id = $_SESSION['user']['id'];
            return User::findById($id);
        }
        return null;
    }

    public static function check(): bool
    {
        return true;
        if ($_SESSION['user']['id'] == str_replace("Bearer ", "", $_SERVER["HTTP_AUTHORIZATION"])) {
            return true;
        }
        return false;
//      $token = str_replace("Bearer ", "", $_SERVER["HTTP_AUTHORIZATION"]);
//      return $_SESSION['user']['id'] == $token;
//        return isset($_SESSION['user']['id']) && self::user() !== null;
    }

    public static function logout(): void
    {
        unset($_SESSION['user']['id']);
    }
}
