<?php

namespace Lararole\Http\Controllers;

use Illuminate\Routing\Controller;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('welcome');
    }
}
