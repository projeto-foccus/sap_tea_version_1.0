<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerfilProfissional extends Model
{
    /**
     * Nome da tabela no banco de dados
     *
     * @var string
     */
    protected $table = 'perfil_profissional';

    /**
     * Define os campos que podem ser preenchidos em massa (mass assignment)
     *
     * @var array
     */
    protected $fillable = [
        'nome_profissional',
        'especialidade_profissional',
        'observacoes_profissional',
        'fk_id_aluno',
        'data_cadastro_profissional'
    ];

    /**
     * Define os campos que devem ser convertidos para tipos nativos
     *
     * @var array
     */
    protected $casts = [
        'data_cadastro_profissional' => 'date',
    ];

    /**
     * Define o relacionamento com o modelo Aluno
     */
    public function aluno()
    {
        return $this->belongsTo(Aluno::class, 'fk_id_aluno', 'alu_id');
    }

    /**
     * Define a data de cadastro automaticamente antes de criar o registro
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->data_cadastro_profissional = $model->data_cadastro_profissional ?? now()->toDateString();
        });
    }
}
