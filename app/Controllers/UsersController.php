<?php

namespace App\Controllers;

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
        $params['role_id'] = 1;
        $user = new User($params);

        if ($user->isValid()) {
            if ($user->save()) {
                echo "User created successfully!";
            } else {
                echo "Error creating user!";
            }
        } else {
            echo "Validation failed!";
            print_r($user->errors());
        }
    }

    public function login(Request $request): void
    {
        $params = $request->getBody();
        $user = User::findByUniversityRegistry($params['university_registry']);
        if ($user && $user->authenticate($params['password'])) {
            Auth::login($user);
            echo "user logged in successfully!";
        } else {
            echo "invalid university_registry or password";
        }
    }

    public function destroy(): void
    {
          Auth::logout();
          echo "user logged out successfully!";
    }
}
