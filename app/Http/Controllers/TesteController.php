<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TesteController extends Controller
{
    public function teste(Request $request)
    {
        return $request->all(); // Retorna todos os dados da requisição
    }
}
