<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\PerfilEstudante;
use App\Models\PerfilProfissional;

class Aluno extends Model
{
    public $timestamps = false;
    
    public function escola()
    {
        return $this->belongsTo(\App\Models\Escola::class, 'fk_escola_id', 'esc_id');
    }

    protected $table = 'aluno';
    protected $primaryKey = 'alu_id';

    // Relacionamento com a tabela matricula
    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'fk_id_aluno', 'alu_id');
    }
    
    /**
     * Relacionamento com o perfil do estudante
     */
    public function perfilEstudante()
    {
        return $this->hasOne(PerfilEstudante::class, 'fk_id_aluno', 'alu_id');
    }
    
    /**
     * Relacionamento com os profissionais
     */
    public function profissionais()
    {
        return $this->hasMany(PerfilProfissional::class, 'fk_id_aluno', 'alu_id');
    }

    public static function getAlunosDetalhados($id)
    {
        $query = "SELECT DISTINCT 
                      alu.alu_id, 
                      alu.alu_nome, 
                      alu.alu_dtnasc, 
                      alu.alu_ra,
                      mat.numero_matricula,
                      esc.esc_inep, 
                      alu.alu_nome_resp,
                      alu.alu_tipo_parentesco,
                      alu.alu_tel_resp,
                      alu.alu_email_resp,
                      esc.esc_razao_social,
                      tm.desc_modalidade, -- Corrigido para buscar da tabela tipo_modalidade
                      ser.serie_desc,
                  mat.periodo,
                      fk_cod_valor_turma,
                      org.org_razaosocial,
                      moda.id_modalidade,
                      fun.func_nome, 
                      tp.desc_tipo_funcao
                  FROM aluno AS alu
                  LEFT JOIN matricula AS mat ON alu.alu_id = mat.fk_id_aluno
                  LEFT JOIN modalidade AS moda ON mat.fk_cod_mod = moda.id_modalidade
                  LEFT JOIN tipo_modalidade AS tm ON moda.fk_id_modalidade = tm.id_tipo_modalidade
                  LEFT JOIN turma AS tur ON tur.cod_valor = mat.fk_cod_valor_turma
                  LEFT JOIN funcionario AS fun ON fun.func_id = tur.fk_cod_func
                  LEFT JOIN escola AS esc ON CONVERT(esc.esc_inep USING utf8mb4) COLLATE utf8mb4_unicode_ci = CONVERT(tur.fk_inep USING utf8mb4) COLLATE utf8mb4_unicode_ci
                  LEFT JOIN tipo_funcao AS tp ON tp.tipo_funcao_id = fun.func_cod_funcao
                  LEFT JOIN serie as ser ON ser.serie_id = mat.fk_id_serie
                  LEFT JOIN orgao AS org ON org.org_id = esc.fk_org_esc_id
                  WHERE alu.alu_id =?";

        return DB::select($query, [$id]);
    }
    // Model Aluno.php
public function eixoComunicacao()
{
    return $this->hasOne(EixoComunicacaoLinguagem::class, 'fk_alu_id_ecomling');
}

public function eixoComportamento()
{
    return $this->hasOne(EixoComportamento::class, 'fk_alu_id_ecomp');
}

public function eixoSocioEmocional()
{
    return $this->hasOne(EixoInteracaoSocEmocional::class, 'fk_alu_id_eintsoc');
}

public function preenchimento()
{
    return $this->hasOne(PreenchimentoInventario::class, 'fk_id_aluno');
}

/**
 * Relacionamento com o responsável
 */
public function responsavel()
{
    return $this->hasOne(Responsavel::class, 'aluno_id', 'alu_id');
}

    /**
     * Relacionamento com as atividades de comunicação/linguagem
     */
    public function atividadesComunicacao()
    {
        return $this->hasMany(AtividadeComunicacao::class, 'aluno_id');
    }
    
    /**
     * Relacionamento com as atividades de comportamento
     */
    public function atividadesComportamento()
    {
        return $this->hasMany(AtividadeComportamento::class, 'aluno_id');
    }
    
    /**
     * Relacionamento com as atividades socioemocionais
     */
    public function atividadesSocioemocionais()
    {
        return $this->hasMany(AtividadeSocioemocional::class, 'aluno_id');
    }

    /**
     * Scope para filtrar alunos que estejam em turmas de um determinado professor (funcionario).
     * Uso: Aluno::porProfessor($funcId)->get();
     */
    public function scopePorProfessor($query, $funcId)
    {
        return $query->whereHas('matriculas.turma', function($q) use ($funcId) {
            $q->where('fk_cod_func', $funcId);
        });
    }

    /**
     * Scope para alunos em turma do professor logado E turma vinculada a enturmação
     */
    public function scopePorProfessorEnturmado($query, $funcId)
    {
        return $query->whereHas('matriculas.turma', function($q) use ($funcId) {
            $q->where('fk_cod_func', $funcId)
              ->whereHas('enturmacao');
        });
    }
}
