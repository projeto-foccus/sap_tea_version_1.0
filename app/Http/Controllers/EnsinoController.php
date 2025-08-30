<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EnsinoController extends Controller
{
    public function inicial()
    {
        return view('modalidade-ensino.inicial');
    }
}
