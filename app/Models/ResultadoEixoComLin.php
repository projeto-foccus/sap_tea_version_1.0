<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResultadoEixoComLin extends Model
{
    protected $table = 'result_eixo_com_lin';
    protected $primaryKey = 'id_result_eixo_com_lin';
    public $timestamps = false;

    protected $fillable = [
        'fk_hab_pro_com_lin',
        'fk_id_pro_com_lin',
        'fk_result_alu_id_ecomling',
        'date_cadastro',
        'tipo_fase_com_lin'
    ];

    /**
     * Relacionamento com a tabela de alunos
     */
    public function aluno()
    {
        return $this->belongsTo(Aluno::class, 'fk_result_alu_id_ecomling', 'alu_id');
    }

    /**
     * Relacionamento com a tabela de propostas
     */
    public function proposta()
    {
        return $this->belongsTo(PropostaComLin::class, 'fk_id_pro_com_lin', 'id_pro_com_lin');
    }

    /**
     * Relacionamento com a tabela de habilidades/propostas
     */
    public function habilidadeProposta()
    {
        return $this->belongsTo(HabProComLin::class, 'fk_hab_pro_com_lin', 'id_hab_pro_com_lin');
    }
}
