<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aluno; // Certifique-se de importar o modelo Aluno

class ControllerPerfil extends Controller
{
    public function imprimeAluno(Request $request)
    {
        // Captura o nome pesquisado (se houver)
        $nome = $request->input('nome', '');

        // Busca os alunos no banco de dados com paginação
        $alunos = Aluno::where('alu_nome', 'like', "%$nome%")->paginate(10);

        // Retorna a view com os dados dos alunos
        return view('alunos.imprime_aluno', compact('alunos'));
    }
}