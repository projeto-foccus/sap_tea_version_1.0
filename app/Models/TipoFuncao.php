<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoFuncao extends Model
{
    protected $table = 'tipo_funcao';
    protected $primaryKey = 'tipo_funcao_id';

    public function funcionarios()
    {
        return $this->hasMany(Funcionario::class, 'func_cod_funcao', 'tipo_funcao_id');
    }
}
