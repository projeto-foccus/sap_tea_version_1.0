<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EscolaController extends Controller
{
    public function index()
    {
        return view('escola.index'); // Retorna a view da Escola
    }
}