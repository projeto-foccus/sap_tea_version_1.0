<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{
    // Relacionamento: Turma pertence a uma Enturmacao
    public function enturmacao()
    {
        return $this->belongsTo(Enturmacao::class, 'fk_cod_enturmacao', 'id_enturmacao');
    }
    protected $table = 'turma';
    protected $primaryKey = 'cod_valor';

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class, 'fk_cod_func', 'func_id');
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'fk_cod_valor_turma', 'cod_valor');
    }

    // Relacionamento: Turma pertence a uma Escola
    public function escola()
    {
        // Ajuste o campo 'fk_inep' para o nome correto caso seja diferente
        return $this->belongsTo(Escola::class, 'fk_inep', 'esc_inep');
    }
}
