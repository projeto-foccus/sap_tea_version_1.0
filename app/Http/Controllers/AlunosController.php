<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aluno;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AlunosController extends Controller
{
    public function index()
    {
        // Obter o professor logado
        $professor_logado = auth('funcionario')->user();
        $professor_id = $professor_logado ? $professor_logado->func_id : null;
        $professor_nome = $professor_logado ? $professor_logado->func_nome : 'Não identificado';
        
        // Buscar alunos relacionados ao professor logado com eager loading
        $alunos = Aluno::with(['matriculas.turma.escola', 'matriculas.modalidade.tipo'])
            ->whereHas('matriculas.turma', function($query) use ($professor_id) {
                $query->where('fk_cod_func', $professor_id);
            })
            ->orderBy('alu_nome')
            ->get();
        
        $titulo = 'Lista de Estudantes';
        
        // Configurar botões de ação para cada aluno
        $botoes = [
            [
                'rota' => 'atualiza.perfil.estudante',
                'label' => 'Perfil do Estudante',
                'classe' => function($aluno) {
                    return $aluno->flag_perfil == 'S' ? 'btn-success' : 'btn-danger';
                }
            ]
        ];
        
        return view('familia.lista_alunos', compact('alunos', 'titulo', 'professor_nome', 'botoes'));
    }
}
