<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Responsavel extends Model
{
    protected $table = 'responsaveis';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'aluno_id',
        'nome',
        'telefone',
        'email',
        'parentesco'
    ];

    /**
     * Relacionamento com o aluno
     */
    public function aluno()
    {
        return $this->belongsTo(Aluno::class, 'aluno_id', 'alu_id');
    }
}
