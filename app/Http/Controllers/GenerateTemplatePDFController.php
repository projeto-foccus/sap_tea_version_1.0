<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\File;

class GenerateTemplatePDFController extends Controller
{
    public function generateTemplate()
    {
        // Cria um PDF vazio
        $pdf = PDF::loadView('pdf.template');
        
        // Salva o PDF no diretório public
        $pdf->save(public_path('img/template_pdf.pdf'));
        
        // Converte o PDF para PNG usando imagemagick
        exec('convert -density 300 "'.public_path('img/template_pdf.pdf').'" "'.public_path('img/template_pdf.png').'"');
        
        return response()->json(['success' => true]);
    }
}
