<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SondagemInicialController extends Controller
{
    public function inicial()
    {
        return view('sondagem.inicial');
    }

    public function continuada1()
    {
        return view('sondagem.continuada1');
    }

    public function continuada2()
    {
        return view('sondagem.continuada2');
    }

    public function final()
    {
        return view('sondagem.final');
    }
}
