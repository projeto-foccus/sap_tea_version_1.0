<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comunicacao extends Model
{
        protected $table = 'comunicacao';
        protected $primaryKey = 'id_comunicacao';

        protected $fillable=['precisa_comunicacao','entende_instrucao','recomenda_instrucao','fk_id_aluno'];
        public $timestamps =false;
}
