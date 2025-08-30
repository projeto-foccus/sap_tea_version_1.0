<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerfilFamilia extends Model
{
    protected $table = 'perfil_familia';
    protected $primaryKey = 'id_perfil_familia';

    protected $fillable = ['expectativa_05','estrategia_05','crise_esta_05','fk_id_aluno'
];
public $timestamps = false;


}
