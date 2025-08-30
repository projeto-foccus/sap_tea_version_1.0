<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modalidade extends Model
{
    protected $table = 'modalidade';
    protected $primaryKey = 'id_modalidade';

    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'fk_cod_mod', 'id_modalidade');
    }

    // Relacionamento com TipoModalidade
    public function tipo()
    {
        return $this->belongsTo(TipoModalidade::class, 'fk_id_modalidade', 'id_tipo_modalidade');
    }
}
