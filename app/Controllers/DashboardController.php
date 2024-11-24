<?php

namespace App\Controllers;

use App\Models\User;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\Authentication\Auth;

use function json_encode;

class DashboardController extends Controller
{
    public function index(): void
    {
        echo "dashboard somente para autorizados.";
    }
}
