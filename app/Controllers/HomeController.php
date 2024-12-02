<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\FlashMessage;

class HomeController extends Controller
{
    public function index(Request $request): void
    {
        $title = 'classlog';

        $this->render('home/index', compact('title'));
    }
}
