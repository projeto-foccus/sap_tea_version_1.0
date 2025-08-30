<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    protected $table = 'matricula';

    // Relacionamento corrigido com Aluno (chave estrangeira: fk_id_aluno → chave primária de Aluno: alu_id)
    public function aluno()
    {
        return $this->belongsTo(Aluno::class, 'fk_id_aluno', 'alu_id');
    }

    // Relacionamento com Modalidade (chave estrangeira: fk_cod_mod → chave primária de Modalidade: id_modalidade)
    public function modalidade()
    {
        return $this->belongsTo(Modalidade::class, 'fk_cod_mod', 'id_modalidade');
    }

    // Relacionamento com Turma (chave estrangeira: fk_cod_valor_turma → chave primária de Turma: cod_valor)
    public function turma()
    {
        return $this->belongsTo(Turma::class, 'fk_cod_valor_turma', 'cod_valor');
    }
}
