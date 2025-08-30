<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VisualizaInventarioEstudanteController extends Controller
{
    public function visualiza_inventario(){

        return view('sondagem.inventarios');

    }
}
