<?php

namespace App\Controllers;

use App\Modules\View;

class HomeController extends Controller
{
    public function index()
    {
        return View::display('index.twig', ['name' => '']);
    }
}