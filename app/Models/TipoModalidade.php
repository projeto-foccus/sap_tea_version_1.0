<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoModalidade extends Model
{
    protected $table = 'tipo_modalidade';
    protected $primaryKey = 'id_tipo_modalidade';
    public $timestamps = false;

    protected $fillable = [
        'desc_modalidade'
    ];

    // Relacionamento inverso (opcional)
    // public function modalidades()
    // {
    //     return $this->hasMany(Modalidade::class, 'fk_id_modalidade', 'id_tipo_modalidade');
    // }
}
