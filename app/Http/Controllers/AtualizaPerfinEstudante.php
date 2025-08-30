<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Aluno;

class AtualizaPerfinEstudante extends Controller
{

    public function atualizaPerfil($id)
{
    // Consulta para obter dados do aluno

$query = "SELECT alu.alu_id, alu.alu_nome, alu.alu_dtnasc, alu.alu_ra,
                     tm.desc_modalidade,
                     ser.serie_desc AS desc_serie_modalidade,
                     fun.func_nome, tp.desc_tipo_funcao, tur.fk_cod_func
              FROM aluno AS alu
              LEFT JOIN matricula AS mat ON alu.alu_id = mat.fk_id_aluno
              LEFT JOIN modalidade AS moda ON mat.fk_cod_mod = moda.id_modalidade
              LEFT JOIN tipo_modalidade AS tm ON moda.fk_id_modalidade = tm.id_tipo_modalidade
              LEFT JOIN serie AS ser ON ser.fk_mod_id = mat.fk_cod_mod
              LEFT JOIN turma AS tur ON tur.cod_valor = mat.fk_cod_valor_turma
              LEFT JOIN funcionario AS fun ON fun.func_id = 37
              LEFT JOIN tipo_funcao AS tp ON tp.tipo_funcao_id = fun.func_cod_funcao
              WHERE alu.alu_id = ?";
    
    $dados= DB::select($query, [$id]);
   // dd($dados);
    if (empty($dados)) {
        abort(404); // Aluno não encontrado
    }

    // Valores padrão para o perfil
    $defaultPerfil = [
        'diag_laudo' => 0,
        'cid' => null,
        'nome_medico' => null,
        'data_laudo' => null,
        'nivel_suporte' => null,
        'uso_medicamento' => 0,
        'quais_medicamento' => null,
        'nec_pro_apoio' => 0,
        'prof_apoio' => 0,
        'loc_01' => 0,
        'hig_02' => 0,
        'ali_03' => 0,
        'com_04' => 0,
        'out_05' => 0,
        'out_momentos' => null,
        'at_especializado' => 0,
        'nome_prof_AEE' => null,
        's_auditiva' => 0,
        's_visual' => 0,
        's_tatil' => 0,
        's_outros' => 0,
        'maneja_04' => null,
        'asa_04' => 0,
        'alimentos_pref_04' => null,
        'alimento_evita_04' => null,
        'contato_pc_04' => null,
        'reage_contato' => null,
        'interacao_escola_04' => null,
        'interesse_atividade_04' => null,
        'aprende_visual_04' => 0,
        'recurso_auditivo_04' => 0,
        'material_concreto_04' => 0,
        'outro_identificar_04' => 0,
        'descricao_outro_identificar_04' => null,
        'realiza_tarefa_04' => null,
        'mostram_eficazes_04' => null,
        'prefere_ts_04' => null,
        'expectativa_05' => null,
        'estrategia_05' => null,
        'crise_esta_05' => null,
        'precisa_comunicacao' => 0,
        'entende_instrucao' => 0,
        'recomenda_instrucao' => null
    ];
    
    // Consulta para obter dados do perfil estudante
    $perfil = DB::table('perfil_estudante')
        ->where('fk_id_aluno', $id)
        ->first();
    
    // Se não existir perfil, cria um objeto vazio
    if (!$perfil) {
        $perfil = (object) $defaultPerfil;
    } else {
        // Garante que todas as propriedades necessárias existam no perfil
        $perfil = (object) array_merge($defaultPerfil, (array) $perfil);
    }
    
    // Busca os dados adicionais das outras tabelas
    $personalidade = DB::table('personalidade')
        ->where('fk_id_aluno', $id)
        ->first();
        
    $preferencia = DB::table('preferencia')
        ->where('fk_id_aluno', $id)
        ->first();
        
    $comunicacao = DB::table('comunicacao')
        ->where('fk_id_aluno', $id)
        ->first();
        
    $perfilFamilia = DB::table('perfil_familia')
        ->where('fk_id_aluno', $id)
        ->first();
        
    // Combina todos os dados em um único objeto
    $results = (object) array_merge(
        (array) $perfil,
        (array) $personalidade,
        (array) $preferencia,
        (array) $comunicacao,
        (array) $perfilFamilia
    );
    

    // Busca o aluno pelo id
    $aluno = \App\Models\Aluno::find($id);
    // Redireciona para a view com os dados
    return view('alunos.atualiza_perfil_estudante', [
        'aluno' => $aluno,
        'dados' => $dados,
        'perfil' => $results,
        'results' => [$results] // Mantido para compatibilidade com código existente
    ]);
}

    



}
