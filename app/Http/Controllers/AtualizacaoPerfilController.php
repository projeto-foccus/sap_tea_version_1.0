<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PerfilEstudante;
use App\Models\PersonalidadeAluno;
use App\Models\Comunicacao;
use App\Models\Preferencia;
use App\Models\PerfilFamilia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AtualizacaoPerfilController extends Controller
{
    /**
     * Atualiza o perfil do estudante e redireciona para a página do perfil.
     */
    public function AtualizaPerfil(Request $request, $id)
{
    try {
        // Log para depuração
        Log::info('Dados recebidos para atualização do perfil:', [
            'id' => $id,
            'request_all' => $request->all()
        ]);
        
        DB::beginTransaction();

        // Atualiza perfil_estudante
        $perfil = \App\Models\PerfilEstudante::firstOrNew(['fk_id_aluno' => $id]);
        
        // Obtém os dados do request
        $dadosPerfil = $request->only([
            'diag_laudo', 'cid', 'nome_medico', 'data_laudo', 'nivel_suporte',
            'uso_medicamento', 'quais_medicamento', 'nec_pro_apoio', 'prof_apoio',
            'loc_01', 'hig_02', 'ali_03', 'com_04', 'out_05', 'out_momentos',
            'at_especializado', 'nome_prof_AEE', 'update_count'
        ]);

        // Tratamento robusto para todos os campos do perfil_estudante
        $camposInt = [
            'diag_laudo', 'nivel_suporte', 'uso_medicamento', 'nec_pro_apoio', 'prof_apoio',
            'loc_01', 'hig_02', 'ali_03', 'com_04', 'out_05'
        ];
        foreach ($camposInt as $campo) {
            if (!isset($dadosPerfil[$campo]) || $dadosPerfil[$campo] === '' || is_null($dadosPerfil[$campo])) {
                $dadosPerfil[$campo] = 0;
            }
        }
        // Campos texto
        $camposTexto = ['cid','nome_medico','quais_medicamento','out_momentos','at_especializado'];
        foreach ($camposTexto as $campo) {
            if (!isset($dadosPerfil[$campo]) || is_null($dadosPerfil[$campo])) {
                $dadosPerfil[$campo] = '';
            }
        }
        // Campos de data/datetime
        if (!isset($dadosPerfil['data_laudo']) || $dadosPerfil['data_laudo'] === '' || is_null($dadosPerfil['data_laudo'])) {
            $dadosPerfil['data_laudo'] = null;
        }
        // Garante que outros campos sensíveis nunca sejam null
        $camposNuncaNull = ['cid','nome_medico','quais_medicamento','prof_apoio','out_momentos','at_especializado','nome_prof_AEE'];
        foreach ($camposNuncaNull as $campo) {
            if (!isset($dadosPerfil[$campo]) || is_null($dadosPerfil[$campo])) {
                $dadosPerfil[$campo] = '';
            }
        }
        // nec_pro_apoio deve ser inteiro
        if (!isset($dadosPerfil['nec_pro_apoio']) || $dadosPerfil['nec_pro_apoio'] === '' || is_null($dadosPerfil['nec_pro_apoio'])) {
            $dadosPerfil['nec_pro_apoio'] = 0;
        }
        // uso_medicamento deve ser inteiro
        if (!isset($dadosPerfil['uso_medicamento']) || $dadosPerfil['uso_medicamento'] === '' || is_null($dadosPerfil['uso_medicamento'])) {
            $dadosPerfil['uso_medicamento'] = 0;
        }
        // Campos de data/datetime devem ser null se vazios
        if (!isset($dadosPerfil['data_laudo']) || $dadosPerfil['data_laudo'] === '' || is_null($dadosPerfil['data_laudo'])) {
            $dadosPerfil['data_laudo'] = null;
        }
        // update_count deve ser inteiro
        if (!isset($dadosPerfil['update_count']) || $dadosPerfil['update_count'] === '' || is_null($dadosPerfil['update_count'])) {
            $dadosPerfil['update_count'] = 0;
        }
        // Campos booleanos/inteiros opcionais
        $camposInt = ['loc_01','hig_02','ali_03','com_04','out_05'];
        foreach ($camposInt as $campo) {
            if (!isset($dadosPerfil[$campo]) || $dadosPerfil[$campo] === '' || is_null($dadosPerfil[$campo])) {
                $dadosPerfil[$campo] = 0;
            }
        }
        // Define valor padrão para nivel_suporte se não estiver preenchido
        if (empty($dadosPerfil['nivel_suporte'])) {
            $dadosPerfil['nivel_suporte'] = 1; // Valor padrão: Nível 1 - Exige pouco apoio
        }
        
        $perfil->fill($dadosPerfil);
        $perfil->fk_id_aluno = $id;
        $perfil->save();

        // Atualiza personalidade
        $personalidade = \App\Models\PersonalidadeAluno::firstOrNew(['fk_id_aluno' => $id]);
        $personalidade->fill($request->only([
            'carac_principal', 'inter_princ_carac', 'livre_gosta_fazer',
            'feliz_est', 'trist_est', 'obj_apego'
        ]));
        $personalidade->fk_id_aluno = $id;
        $personalidade->save();

        // Atualiza preferencia
        $this->atualizarPreferencia($request, $id);

        // Atualiza comunicacao
        $comunicacao = \App\Models\Comunicacao::firstOrNew(['fk_id_aluno' => $id]);
        $comunicacao->fill($request->only([
            'precisa_comunicacao', 'entende_instrucao', 'recomenda_instrucao'
        ]));
        $comunicacao->fk_id_aluno = $id;
        $comunicacao->save();

        // Atualiza perfil_familia
        $familia = \App\Models\PerfilFamilia::firstOrNew(['fk_id_aluno' => $id]);
        $familia->fill($request->only([
            'expectativa_05', 'estrategia_05', 'crise_esta_05'
        ]));
        $familia->fk_id_aluno = $id;
        $familia->save();

        // Atualiza perfil_profissional
        // Primeiro, vamos remover os profissionais existentes para este aluno
        \App\Models\PerfilProfissional::where('fk_id_aluno', $id)->delete();
        
        // Agora vamos adicionar os novos profissionais
        for ($i = 1; $i <= 3; $i++) {
            $numPad = str_pad($i, 2, '0', STR_PAD_LEFT); // 1 -> 01, 2 -> 02, etc.
            $nomeProfissional = $request->input('nome_profissional_' . $numPad);
            
            // Só salvamos se houver pelo menos o nome do profissional
            if (!empty($nomeProfissional)) {
                $profissional = new \App\Models\PerfilProfissional();
                $profissional->nome_profissional = $nomeProfissional;
                $profissional->especialidade_profissional = $request->input('especialidade_profissional_' . $numPad) ?? '';
                $profissional->observacoes_profissional = $request->input('observacoes_profissional_' . $numPad) ?? '';
                $profissional->fk_id_aluno = $id;
                $profissional->save();
            }
        }

        // Atualiza o campo flag_perfil para indicar que o perfil foi cadastrado
        DB::table('aluno')
            ->where('alu_id', $id)
            ->update(['flag_perfil' => '*']);

        DB::commit();

        // Verifica se é uma requisição AJAX
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => 'Perfil cadastrado com sucesso!',
                'redirect' => route('perfil.estudante.independente')
            ]);
        }
        
        // Redireciona de volta para a listagem de alunos
        return redirect()->route('perfil.estudante.independente')->with('success', 'Perfil cadastrado com sucesso!');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Erro ao atualizar perfil: ' . $e->getMessage(), ['exception' => $e]);
        
        // Verifica se é uma requisição AJAX
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar perfil: ' . $e->getMessage()
            ], 500);
        }
        
        return redirect()->back()
            ->with('error', 'Erro ao atualizar perfil: ' . $e->getMessage())
            ->withInput();
    }
}


    /**
     * Atualiza os dados na tabela PerfilEstudante.
     */
    private function atualizarPerfilEstudante($request, $id)
    {
        $perfil = PerfilEstudante::updateOrCreate(
            ['fk_id_aluno' => $id],
            $request->all()
        );
        
        return $perfil;
    }

    /**
     * Atualiza os dados na tabela PersonalidadeAluno.
     */
    private function atualizarPersonalidade($request, $id)
    {
        $model = PersonalidadeAluno::where('fk_id_aluno', $id)->firstOrFail();

        $model->update([
            'carac_principal' => $request->carac_principal,
            'inter_princ_carac' => $request->inter_princ_carac,
            'livre_gosta_fazer' => $request->livre_gosta_fazer,
            'feliz_est' => $request->feliz_est,
            'trist_est' => $request->trist_est,
            'obj_apego' => $request->obj_apego
        ]);
    }

    /**
     * Atualiza os dados na tabela Comunicacao.
     */
    private function atualizarComunicacao($request, $id)
    {
        $model = Comunicacao::where('fk_id_aluno', $id)->firstOrFail();
        
        $model->update([
            'precisa_comunicacao' => $request->precisa_comunicacao,
            'entende_instrucao' => $request->entende_instrucao,
            'recomenda_instrucao' => $request->recomenda_instrucao
        ]);
    }

    /**
     * Atualiza os dados na tabela Preferencia.
     */
    private function atualizarPreferencia($request, $id)
    {
        $model = Preferencia::firstOrNew(['fk_id_aluno' => $id]);

        $model->fill([
            'auditivo_04' => $request->has('auditivo_04') ? 1 : 0,
            'visual_04' => $request->has('visual_04') ? 1 : 0,
            'tatil_04' => $request->has('tatil_04') ? 1 : 0,
            'outros_04' => $request->has('outros_04') ? 1 : 0,
            'maneja_04' => $request->maneja_04,
            'asa_04' => $request->asa_04,
            'alimentos_pref_04' => $request->alimentos_pref_04,
            'alimento_evita_04' => $request->alimento_evita_04,
            'contato_pc_04' => $request->contato_pc_04,
            'reage_contato' => $request->reage_contato,
            'interacao_escola_04' => $request->interacao_escola_04,
            'interesse_atividade_04' => $request->interesse_atividade_04,
            'aprende_visual_04' => $request->has('aprende_visual_04') ? 1 : 0,
            'recurso_auditivo_04' => $request->has('recurso_auditivo_04') ? 1 : 0,
            'material_concreto_04' => $request->has('material_concreto_04') ? 1 : 0,
            'outro_identificar_04' => $request->has('outro_identificar_04') ? 1 : 0,
            'descricao_outro_identificar_04' => $request->descricao_outro_identificar_04,
            'realiza_tarefa_04' => $request->realiza_tarefa_04,
            'mostram_eficazes_04' => $request->mostram_eficazes_04,
            'prefere_ts_04' => $request->prefere_ts_04
        ]);
        $model->fk_id_aluno = $id;
        $model->save();
    }
}
