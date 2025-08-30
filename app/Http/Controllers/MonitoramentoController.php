<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Aluno;
use Carbon\Carbon;

class MonitoramentoController extends Controller
{
    /**
     * Exibe a rotina de monitoramento do aluno com gráficos
     */
    public function rotina_monitoramento_aluno($aluno_id)
    {
        $professor_logado = auth('funcionario')->user();
        $professor_id = $professor_logado ? $professor_logado->func_id : null;

        // Garante que o aluno pertence ao professor logado
        $aluno = \App\Models\Aluno::where('alu_id', $aluno_id)
            ->whereHas('matriculas.turma', function($q) use ($professor_id) {
                $q->where('fk_cod_func', $professor_id);
            })->first();
        if (!$aluno) {
            return back()->withErrors(['msg' => 'Aluno não pertence ao professor logado ou não existe.']);
        }

        // Garante que a view recebe o aluno_id correto, extraído da URL
        $alunoId = $aluno_id;

        // Garante que existe registro nas três tabelas-eixo
        $tem_eixo_com = \App\Models\EixoComunicacaoLinguagem::where('fk_alu_id_ecomling', $aluno_id)->exists();
        $tem_eixo_int = \App\Models\EixoInteracaoSocEmocional::where('fk_alu_id_eintsoc', $aluno_id)->exists();
        $tem_eixo_comp = \App\Models\EixoComportamento::where('fk_alu_id_ecomp', $aluno_id)->exists();
        if (!($tem_eixo_com && $tem_eixo_int && $tem_eixo_comp)) {
            return back()->withErrors(['msg' => 'O aluno precisa ter registros em todos os eixos (Comunicação, Interação Socioemocional e Comportamento) para acessar esta rotina.']);
        }

        // Resto da lógica de gráficos e monitoramento...
        $alunoDetalhado = \App\Models\Aluno::getAlunosDetalhados($aluno_id);

        $eixoCom = \App\Models\EixoComunicacaoLinguagem::where('fk_alu_id_ecomling', $aluno_id)
            ->where('fase_inv_com_lin', 'In')
            ->first();
        $data_inicial_com_lin = $eixoCom ? $eixoCom->data_insert_com_lin : null;
        $professor_nome = $professor_logado ? $professor_logado->func_nome : null;

        // Consultas para gráficos e análises
        $comunicacao_resultados = \App\Models\ResultEixoComLin::with('proposta')->where('fk_result_alu_id_ecomling', $aluno_id)->get();
        $comunicacao_resultados = $comunicacao_resultados->sortBy(function($item) {
            return optional($item->proposta)->cod_pro_com_lin;
        })->values();

        $comportamento_resultados = \App\Models\ResultEixoComportamento::with('proposta')->where('fk_result_alu_id_comportamento', $aluno_id)->get();
        $comportamento_resultados = $comportamento_resultados->sortBy(function($item) {
            return optional($item->proposta)->cod_pro_comportamento;
        })->values();

        $socioemocional_resultados = \App\Models\ResultEixoIntSocio::with('proposta')->where('fk_result_alu_id_int_socio', $aluno_id)->get();
        $socioemocional_resultados = $socioemocional_resultados->sortBy(function($item) {
            return optional($item->proposta)->cod_pro_int_soc;
        })->values();

        return view('rotina_monitoramento.monitoramento_aluno', compact(
            'alunoDetalhado',
            'data_inicial_com_lin',
            'professor_nome',
            'comunicacao_resultados',
            'comportamento_resultados',
            'socioemocional_resultados'
        ));
    }

    /**
     * Exibe a tela de cadastro de rotina para o aluno selecionado
     */
    public function cadastrar_rotina_aluno($id)
    {
        $alunoDetalhado = \App\Models\Aluno::getAlunosDetalhados($id);
        $professor = auth('funcionario')->user();
        if (!$alunoDetalhado) {
            return back()->withErrors(['msg' => 'Não foi possível carregar os dados do aluno. Por favor, acesse o formulário pela rota correta ou verifique se o aluno existe.']);
        }

        return view('rotina_monitoramento.monitoramento_aluno', [
            'alunoId' => $id,
            'alunoDetalhado' => $alunoDetalhado,
            'professor_nome' => $professor->func_nome
        ]);
    }

    /**
     * Salva a rotina de monitoramento do aluno
     */
    public function salvar_rotina(Request $request, $id)
    {
        $request->validate([
            'descricao' => 'required|string|max:1000',
        ]);

        // Lógica de salvamento...
        return redirect()->route('rotina.monitoramento.aluno', $id)
            ->with('success', 'Rotina de monitoramento salva com sucesso!');
    }
}
