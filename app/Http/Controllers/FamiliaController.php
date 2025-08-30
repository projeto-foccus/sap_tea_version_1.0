<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aluno;
use Illuminate\Support\Facades\DB;

class FamiliaController extends Controller
{
    /**
     * Exibe o perfil inicial de atividades e habilidades para a família.
     *
     * @param  int  $id O ID do aluno.
     * @return \Illuminate\View\View
     */
    public function perfilInicialAluno($id)
    {
        $aluno = Aluno::findOrFail($id);

        // Desativa o log de consultas para não poluir os logs
        DB::disableQueryLog();

        // Consultas originais do MonitoramentoAtividadeController

        // Consulta customizada para atividades e habilidades realizadas pelo aluno no eixo Comunicação/Linguagem
        // Atividades do aluno no eixo Comunicação/Linguagem
$atividades_comunicacao = DB::table('result_eixo_com_lin as c')
    ->join('atividade_com_lin as d', 'c.fk_id_pro_com_lin', '=', 'd.id_ati_com_lin')
    ->where('c.fk_result_alu_id_ecomling', $aluno->alu_id)
    ->select('d.desc_ati_com_lin as atividade')
    ->distinct()
    ->orderBy('d.desc_ati_com_lin', 'asc')
    ->pluck('atividade')
    ->toArray();

// Habilidades do aluno no eixo Comunicação/Linguagem
$habilidades_comunicacao = DB::table('result_eixo_com_lin as a')
    ->join('habilidade_com_lin as b', 'a.fk_hab_pro_com_lin', '=', 'b.id_hab_com_lin')
    ->where('a.fk_result_alu_id_ecomling', $aluno->alu_id)
    ->select('b.desc_hab_com_lin as habilidade')
    ->distinct()
    ->orderBy('b.desc_hab_com_lin', 'asc')
    ->pluck('habilidade')
    ->toArray();

        // 2. Comportamento
// Atividades do aluno no eixo Comportamento
$atividades_comportamento = DB::table('result_eixo_comportamento as c')
    ->join('atividade_comportamento as d', 'c.fk_id_pro_comportamento', '=', 'd.id_ati_comportamento')
    ->where('c.fk_result_alu_id_comportamento', $aluno->alu_id)
    ->select('d.desc_ati_comportamento as atividade')
    ->distinct()
    ->orderBy('d.desc_ati_comportamento', 'asc')
    ->pluck('atividade')
    ->toArray();

// Habilidades do aluno no eixo Comportamento
$habilidades_comportamento = DB::table('result_eixo_comportamento as a')
    ->join('habilidade_comportamento as b', 'a.fk_hab_pro_comportamento', '=', 'b.id_hab_comportamento')
    ->where('a.fk_result_alu_id_comportamento', $aluno->alu_id)
    ->select('b.desc_hab_comportamento as habilidade')
    ->distinct()
    ->orderBy('b.desc_hab_comportamento', 'asc')
    ->pluck('habilidade')
    ->toArray();



        // 3. Socioemocional
// Atividades do aluno no eixo Socioemocional
$atividades_socioemocional = DB::table('result_eixo_int_socio as c')
    ->join('atividade_int_soc as d', 'c.fk_id_pro_int_socio', '=', 'd.id_ati_int_soc')
    ->where('c.fk_result_alu_id_int_socio', $aluno->alu_id)
    ->select('d.desc_ati_int_soc as atividade')
    ->distinct()
    ->orderBy('d.desc_ati_int_soc', 'asc')
    ->pluck('atividade')
    ->toArray();

// Habilidades do aluno no eixo Socioemocional
$habilidades_socioemocional = DB::table('result_eixo_int_socio as a')
    ->join('habilidade_int_soc as b', 'a.fk_hab_pro_int_socio', '=', 'b.id_hab_int_soc')
    ->where('a.fk_result_alu_id_int_socio', $aluno->alu_id)
    ->select('b.desc_hab_int_soc as habilidade')
    ->distinct()
    ->orderBy('b.desc_hab_int_soc', 'asc')
    ->pluck('habilidade')
    ->toArray();



        // Reativa o log de consultas para o resto da aplicação
        DB::enableQueryLog();

        return view('familia.perfil_familia', [
            'aluno' => $aluno,
            'atividades_comunicacao' => $atividades_comunicacao,
            'habilidades_comunicacao' => $habilidades_comunicacao,
            'atividades_comportamento' => $atividades_comportamento,
            'habilidades_comportamento' => $habilidades_comportamento,
            'atividades_socioemocional' => $atividades_socioemocional,
            'habilidades_socioemocional' => $habilidades_socioemocional,
        ]);
    }
}
