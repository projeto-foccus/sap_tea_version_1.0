<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FaseVerificacaoController extends Controller
{
    /**
     * Retorna alunos filtrados por fase e critérios de progressão
     */
    public function index($fase = null)
    {
        $professor = Auth::guard('funcionario')->user();
        $funcId = $professor->func_id;
        $anoAtual = date('Y');

        if ($fase === 'inicial') {
            // Fase inicial: todos os alunos do professor
            $alunos = \App\Models\Aluno::porProfessor($funcId)
                ->select('aluno.*')
                ->orderBy('aluno.alu_nome', 'asc')
                ->get();
        } else {
            // Outras fases: critérios específicos de progressão
            $query = \App\Models\Aluno::porProfessor($funcId)
                ->join('controle_fases_sondagem', 'aluno.alu_id', '=', 'controle_fases_sondagem.id_aluno')
                ->where('controle_fases_sondagem.ano', $anoAtual);

            switch ($fase) {
                case 'continuada1':
                    $query->where('controle_fases_sondagem.cont_I', 3)
                          ->where('controle_fases_sondagem.fase_cont1', 'Pendente');
                    break;
                case 'continuada2':
                    $query->where('controle_fases_sondagem.cont_fase_c1', 3)
                          ->where('controle_fases_sondagem.fase_cont2', 'Pendente');
                    break;
                case 'final':
                    $query->where('controle_fases_sondagem.cont_fase_c2', 3)
                          ->where('controle_fases_sondagem.fase_final', 'Pendente');
                    break;
            }
            
            $alunos = $query->select('aluno.*')
                ->orderBy('aluno.alu_nome', 'asc')
                ->get();
        }

        // Títulos para cada fase
        $titulos = [
            'inicial' => 'Sondagem Inicial',
            'continuada1' => 'Sondagem 1ª Cont.',
            'continuada2' => 'Sondagem 2ª Cont.',
            'final' => 'Sondagem Final'
        ];

        $titulo = $titulos[$fase] ?? 'Sondagem';

        return view('alunos.imprime_aluno_eixo', [
            'alunos' => $alunos,
            'titulo' => $titulo,
            'rota_acao' => 'alunos.inventario',
            'rota_pdf' => 'visualizar.inventario',
            'exibeBotaoInventario' => true,
            'exibeBotaoPdf' => true,
            'professor_nome' => $professor->func_nome ?? '',
            'fase' => $fase
        ]);
    }

    /**
     * Retorna alunos filtrados por fase e critérios de progressão (método auxiliar)
     */
    public function getAlunosPorFase($fase)
    {
        return $this->index($fase)->getData()['alunos'];
    }
}
