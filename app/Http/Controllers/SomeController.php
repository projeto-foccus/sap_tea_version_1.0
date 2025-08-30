<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SomeController extends Controller
{
    // Método para "Como Eu Sou"
    public function comoEuSou()
    {
        return view('como-eu-sou');  // Exemplo de uma view chamada 'como-eu-sou.blade.php'
    }

    // Método para "Emociômetro"
    public function emociometro()
    {
        return view('emociometro');  // Exemplo de uma view chamada 'emociometro.blade.php'
    }

    // Método para "Minha Rede de Ajuda"
    public function minhaRedeDeAjuda()
    {
        return view('minha-rede-de-ajuda');  // Exemplo de uma view chamada 'minha-rede-de-ajuda.blade.php'
    }
}
