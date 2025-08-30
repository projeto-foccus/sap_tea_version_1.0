<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function baixar($tipo)
    {
        // Mapeie os links reais de cada material
        $links = [
            'como-eu-sou' => 'https://drive.google.com/file/d/1kyfkTmPpUAKA-6p2e6HMf1MmFDOIEhLD/view?usp=drive_link',
            'emocionometro' => 'https://drive.google.com/file/d/152LTZoyhqvlpOxgfkp2Ip7ntyKJ82h0g/view?usp=drive_link',
            'rede-ajuda' => 'https://drive.google.com/file/d/1ocNrr7uqAsp8rAJIMmCuWkpVLvS5bMN3/view?usp=drive_link',
            'turma-supergando' => 'https://drive.google.com/drive/folders/1njjdQeXzAQUn1lfE-XBjhiMtJNfUgP4T?usp=sharing',
        ];

        $titulos = [
            'como-eu-sou' => 'Como Eu Sou',
            'emocionometro' => 'Emocionômetro',
            'rede-ajuda' => 'Minha Rede de Ajuda',
            'turma-supergando' => 'Turma Supergando',
        ];

        if (!isset($links[$tipo])) {
            return view('download_material', [
                'erro' => 'Material não encontrado.',
                'titulo' => 'Material não encontrado',
                'link' => null
            ]);
        }

        // Exibe view amigável com instruções e botão
        return view('download_material', [
            'erro' => null,
            'titulo' => $titulos[$tipo],
            'link' => $links[$tipo]
        ]);
    }
}
