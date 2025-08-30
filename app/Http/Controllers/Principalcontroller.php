<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Principalcontroller extends Controller
{
    public function Principal() {
        return view('site.principal');
    }
}
