<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class downloadController extends Controller
{
    public function index()
    {
        return view('download.index'); // Retorna a view da Instituição
    }
}
