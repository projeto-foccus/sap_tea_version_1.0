<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrgaoController extends Controller
{
    public function index()
    {
        return view('orgao.index'); // Retorna a view 'orgao.index'
    }
}
