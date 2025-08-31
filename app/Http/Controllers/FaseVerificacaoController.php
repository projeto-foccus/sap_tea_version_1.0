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

        // Base da consulta com SELECT, FROM e JOINs iniciais
        $query = "SELECT DISTINCT 
                    alu.alu_id, alu.alu_nome, alu.alu_dtnasc, alu.alu_ra, mat.numero_matricula,
                    esc.esc_inep, alu.alu_nome_resp, alu.alu_tipo_parentesco, alu.alu_tel_resp,
                    alu.alu_email_resp, esc.esc_razao_social, tm.desc_modalidade, ser.serie_desc,
                    mat.periodo, fk_cod_valor_turma, org.org_razaosocial, moda.id_modalidade,
                    fun.func_nome, tp.desc_tipo_funcao,
                    CASE 
                        WHEN EXISTS (
                            SELECT 1 FROM eixo_com_lin ecl 
                            WHERE ecl.fk_alu_id_ecomling = alu.alu_id AND ecl.fase_inv_com_lin = 'In'
                        ) THEN '*' ELSE NULL 
                    END as flag_inventario
                  FROM aluno AS alu
                  LEFT JOIN matricula AS mat ON alu.alu_id = mat.fk_id_aluno
                  LEFT JOIN modalidade AS moda ON mat.fk_cod_mod = moda.id_modalidade
                  LEFT JOIN tipo_modalidade AS tm ON moda.fk_id_modalidade = tm.id_tipo_modalidade
                  LEFT JOIN turma AS tur ON tur.cod_valor = mat.fk_cod_valor_turma
                  LEFT JOIN funcionario AS fun ON fun.func_id = tur.fk_cod_func
                  LEFT JOIN escola AS esc ON CONVERT(esc.esc_inep USING utf8mb4) COLLATE utf8mb4_unicode_ci = CONVERT(tur.fk_inep USING utf8mb4) COLLATE utf8mb4_unicode_ci
                  LEFT JOIN tipo_funcao AS tp ON tp.tipo_funcao_id = fun.func_cod_funcao
                  LEFT JOIN serie as ser ON ser.serie_id = mat.fk_id_serie
                  LEFT JOIN orgao AS org ON org.org_id = esc.fk_org_esc_id";

        $whereClauses = ['fun.func_id = ?'];
        $bindings = [$funcId];

        if ($fase !== 'inicial') {
            $query .= " LEFT JOIN controle_fases_sondagem AS cfs ON alu.alu_id = cfs.id_aluno";
            
            $faseConditions = [
                'continuada1' => "cfs.cont_I = 3 AND cfs.fase_cont1 = 'Pendente'",
                'continuada2' => "cfs.cont_fase_c1 = 3 AND cfs.fase_cont2 = 'Pendente'",
                'final' => "cfs.cont_fase_c2 = 3 AND cfs.fase_final = 'Pendente'"
            ];

            if (isset($faseConditions[$fase])) {
                $whereClauses[] = $faseConditions[$fase];
            }
            
            $whereClauses[] = 'cfs.ano = ?';
            $bindings[] = $anoAtual;
        }

        $query .= " WHERE " . implode(' AND ', $whereClauses);
        $query .= " ORDER BY alu.alu_nome ASC";

        $alunos = collect(DB::select($query, $bindings));

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
