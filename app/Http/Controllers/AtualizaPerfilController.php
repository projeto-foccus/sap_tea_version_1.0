<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PerfilEstudante;
use App\Models\PersonalidadeAluno;
use App\Models\Comunicacao;
use App\Models\Preferencia;
use App\Models\PerfilFamilia;
use Illuminate\Support\Facades\DB;

class AtualizaPerfilController extends Controller
{
    public function atualizaPerfil(Request $request, $id)
    {
        // Log dos dados recebidos
        \Log::info('Dados recebidos no formulário:', [
            'all_data' => $request->all(),
            'validated_data' => $request->validated()
        ]);

        // Log dos dados da sessão
        \Log::info('Sessão:', [
            'session' => session()->all()
        ]);

        // Validação básica dos campos essenciais
        // Validação básica dos campos essenciais
        $request->validate([
            'diag_laudo' => 'nullable|numeric',
            'cid' => 'nullable',
            'nome_medico' => 'nullable',
            'data_laudo' => 'nullable|date',
            'nivel_suporte' => 'nullable',
            'uso_medicamento' => 'nullable|numeric',
            'quais_medicamento' => 'nullable',
            'nec_pro_apoio' => 'nullable|numeric',
            'prof_apoio' => 'nullable|numeric',
            'loc_01' => 'nullable|numeric',
            'hig_02' => 'nullable|numeric',
            'ali_03' => 'nullable|numeric',
            'com_04' => 'nullable|numeric',
            'out_05' => 'nullable|numeric',
            'out_momentos' => 'nullable',
            'at_especializado' => 'nullable|numeric',
            'nome_prof_AEE' => 'nullable',
            'caracteristicas' => 'nullable',
            'areas_interesse' => 'nullable',
            'atividades_livre' => 'nullable',
            'feliz' => 'nullable',
            'triste' => 'nullable',
            'objeto_apego' => 'nullable',
            'precisa_comunicacao' => 'nullable|numeric',
            'entende_instrucao' => 'nullable|numeric',
            'recomenda_instrucao' => 'nullable',
            's_auditiva' => 'nullable|numeric',
            's_visual' => 'nullable|numeric',
            's_tatil' => 'nullable|numeric',
            's_outros' => 'nullable|numeric',
            'maneja_04' => 'nullable',
            'asa_04' => 'nullable|numeric',
            'alimentos_pref_04' => 'nullable',
            'alimento_evita_04' => 'nullable',
            'contato_pc_04' => 'nullable',
            'reage_contato' => 'nullable',
            'interacao_escola_04' => 'nullable',
            'interesse_atividade_04' => 'nullable',
            'aprende_visual_04' => 'nullable|numeric',
            'recurso_auditivo_04' => 'nullable|numeric',
            'material_concreto_04' => 'nullable|numeric',
            'outro_identificar_04' => 'nullable|numeric',
            'descricao_outro_identificar_04' => 'nullable',
            'realiza_tarefa_04' => 'nullable',
            'mostram_eficazes_04' => 'nullable',
            'prefere_ts_04' => 'nullable',
            'expectativa_05' => 'nullable',
            'estrategia_05' => 'nullable',
            'crise_esta_05' => 'nullable'
        ]);

        DB::beginTransaction();
        try {
            // Atualização das tabelas relacionadas ao estudante
            $this->atualizarPerfilEstudante($request, $id);
            $perfil = PerfilEstudante::where('fk_id_aluno', $id)->first();
            $updateCount = $perfil && isset($perfil->update_count) ? $perfil->update_count : 1;
            $this->atualizarPersonalidade($request, $id);
            $this->atualizarComunicacao($request, $id);
            $this->atualizarPreferencia($request, $id);
            $this->atualizarPerfilFamilia($request, $id);

            // Confirma a transação
            DB::commit();

            // Redireciona para a página de perfil do aluno específico
            \Log::info('REQUEST ATUALIZA PERFIL', $request->all());
            return redirect()->route('perfil.estudante.mostrar', ['id' => $id])->with('success', 'Perfil atualizado com sucesso!')->with('updateCount', $updateCount);
        } catch (\Exception $e) {
            // Reverte a transação em caso de erro
            DB::rollBack();

            // Retorna para a página anterior com mensagem de erro
            return redirect()->back()->with('error', 'Erro ao atualizar o perfil. Verifique se todos os campos foram preenchidos corretamente. Erro: ' . $e->getMessage());
        }
    }

    private function atualizarPerfilEstudante($request, $id)
    {
        $perfil = PerfilEstudante::where('fk_id_aluno', $id)->firstOrFail();
        // Incrementa o contador de atualizações
        $perfil->update_count = ($perfil->update_count ?? 0) + 1;
        $perfil->save();

        $perfil->update([
            'diag_laudo' => $request->diag_laudo,
            'cid' => $request->cid,
            'nome_medico' => $request->nome_medico,
            'data_laudo' => $request->data_laudo,
            'nivel_suporte' => $request->nivel_suporte,
            'uso_medicamento' => $request->uso_medicamento,
            'quais_medicamento' => $request->quais_medicamento,
            'nec_pro_apoio' => $request->nec_pro_apoio,
            'prof_apoio' => $request->prof_apoio,
            'loc_01' => $request->has('loc_01') ? 1 : 0,
            'hig_02' => $request->has('hig_02') ? 1 : 0,
            'ali_03' => $request->has('ali_03') ? 1 : 0,
            'com_04' => $request->has('com_04') ? 1 : 0,
            'out_05' => $request->has('out_05') ? 1 : 0,
            'out_momentos' => $request->out_momentos,
            'at_especializado' => $request->at_especializado,
            'nome_prof_AEE' => $request->nome_prof_AEE
        ]);
    }

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

    private function atualizarComunicacao($request, $id)
    {
        $model = Comunicacao::where('fk_id_aluno', $id)->firstOrFail();
        $model->update([
            'precisa_comunicacao' => $request->precisa_comunicacao,
            'entende_instrucao' => $request->entende_instrucao,
            'recomenda_instrucao' => $request->recomenda_instrucao
        ]);
    }

    private function atualizarPreferencia($request, $id)
    {
        $model = Preferencia::where('fk_id_aluno', $id)->firstOrFail();
        $model->update([
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
    }

    private function atualizarPerfilFamilia($request, $id)
    {
        PerfilFamilia::updateOrCreate(
            ['fk_id_aluno' => $id],
            [
                'expectativa_05' => $request->expectativa_05,
                'estrategia_05' => $request->estrategia_05,
                'crise_esta_05' => $request->crise_esta_05
            ]
        );
    }
}
