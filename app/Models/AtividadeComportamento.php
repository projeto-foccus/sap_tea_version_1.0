<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AtividadeComportamento extends Model
{
    /**
     * Nome da tabela no banco de dados
     *
     * @var string
     */
    protected $table = 'cad_ativ_eixo_comportamento';
    
    /**
     * Campos que podem ser preenchidos em massa
     *
     * @var array
     */
    protected $fillable = [
        'aluno_id',
        'cod_atividade',
        'flag',
        'data_monitoramento',
        'observacoes',
        'registro_timestamp',
        'data_aplicacao',
        'realizado',
        'fase_cadastro'
    ];
    
    /**
     * Valores padrão para os atributos
     *
     * @var array
     */
    protected $attributes = [
        'registro_timestamp' => null,
        'flag' => 1  // Valor padrão 1 para o campo flag
    ];
    
    /**
     * Conversões de tipos
     *
     * @var array
     */
    protected $casts = [
        'data_aplicacao' => 'date',
        'realizado' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'registro_timestamp' => 'integer',  // bigint no banco, inteiro no PHP
        'flag' => 'integer'  // flag é um inteiro
    ];
    
    /**
     * Boot do modelo
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Garante que o registro_timestamp seja definido ao criar um novo registro
            if (empty($model->registro_timestamp)) {
                $model->registro_timestamp = now()->timestamp;
            }
        });
    }
    
    /**
     * Relacionamento com o modelo Aluno
     */
    public function aluno()
    {
        return $this->belongsTo(Aluno::class, 'aluno_id');
    }
}