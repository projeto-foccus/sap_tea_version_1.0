<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enturmacao extends Model
{
    protected $table = 'enturmacao';
    protected $primaryKey = 'id_enturmacao';
    public $timestamps = false;

    // Relacionamento: Enturmacao tem muitas turmas
    public function turmas()
    {
        return $this->hasMany(Turma::class, 'fk_cod_enturmacao', 'id_enturmacao');
    }
}
