<?php

namespace App\Controllers;

use App\Models\User;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\Authentication\Auth;

use function json_encode;
use function print_r;

class DashboardController extends Controller
{
    public function index(): void
    {
        echo(Auth::user()->roleName());
        echo "\n";
        echo "dashboard somente para autorizados.";
    }
}
