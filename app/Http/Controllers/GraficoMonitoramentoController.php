<?php

namespace App\Http\Controllers;

use App\Models\ResultadoEixoComLin;
use App\Models\PropostaComLin;
use App\Models\Aluno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GraficoMonitoramentoController extends Controller
{
    /**
     * Exibe o gráfico de monitoramento do eixo Comunicação/Linguagem
     */
    public function graficoEixoComunicacao($alunoId)
    {
        // Busca o aluno
        $alunoDetalhado = Aluno::getAlunosDetalhados($alunoId);
        $alunoDetalhado = Aluno::getAlunosDetalhados($alunoId);
        $aluno = $alunoDetalhado[0] ?? null;
        
        // Busca as propostas de atividades do eixo comunicação/linguagem
        $propostas = PropostaComLin::all();
        
        // Busca os resultados do aluno agrupados por proposta
        $resultados = DB::table('result_eixo_com_lin')
            ->select('proposta_com_lin.cod_pro_com_lin', 'proposta_com_lin.desc_pro_com_lin', DB::raw('COUNT(*) as total'))
            ->join('proposta_com_lin', 'result_eixo_com_lin.fk_id_pro_com_lin', '=', 'proposta_com_lin.id_pro_com_lin')
            ->where('fk_result_alu_id_ecomling', $alunoId)
            ->groupBy('proposta_com_lin.id_pro_com_lin', 'proposta_com_lin.cod_pro_com_lin', 'proposta_com_lin.desc_pro_com_lin')
            ->orderBy('proposta_com_lin.cod_pro_com_lin')
            ->get();
        
        // Agrupamento por tipo de fase (inicial/final)
        $resultadosPorFase = DB::table('result_eixo_com_lin')
            ->select('tipo_fase_com_lin', DB::raw('COUNT(*) as total'))
            ->where('fk_result_alu_id_ecomling', $alunoId)
            ->groupBy('tipo_fase_com_lin')
            ->get();
        
        // Prepara os dados para o gráfico
        $labels = $resultados->pluck('cod_pro_com_lin')->toArray();
        $data = $resultados->pluck('total')->toArray();
        $descricoes = $resultados->pluck('desc_pro_com_lin')->toArray();
        
        // Debug - remover em produção
        Log::info('Dados do gráfico:', [
            'aluno' => $aluno ? $aluno->toArray() : null,
            'resultados' => $resultados ? $resultados->toArray() : null,
            'resultadosPorFase' => $resultadosPorFase ? $resultadosPorFase->toArray() : null,
            'labels' => $labels,
            'data' => $data,
            'descricoes' => $descricoes
        ]);
        
        return view('rotina_monitoramento.grafico_comunicacao', compact(
            'aluno', 
            'labels', 
            'data', 
            'descricoes',
            'resultados',
            'resultadosPorFase'
        ));
    }
}
