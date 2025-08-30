<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class AtividadeSocioemocional extends Model
{
    /**
     * Nome da tabela no banco de dados
     *
     * @var string
     */
    protected $table = 'cad_ativ_eixo_int_socio';
    
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
        'data_aplicacao',
        'realizado',
        'observacoes',
        'registro_timestamp',
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
     * Relacionamento com o modelo Aluno
     */
    public function aluno()
    {
        return $this->belongsTo(Aluno::class, 'aluno_id');
    }
    
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            Log::debug('Criando registro AtividadeSocioemocional', [
                'dados' => $model->toArray(),
                'attributes' => $model->getAttributes(),
                'observacoes' => $model->observacoes,
                'exists' => $model->exists,
                'isDirty' => $model->isDirty('observacoes'),
                'original' => $model->getOriginal('observacoes', 'N/A')
            ]);
        });
        
        static::created(function ($model) {
            Log::debug('Registro AtividadeSocioemocional criado', [
                'id' => $model->id,
                'observacoes' => $model->observacoes,
                'attributes' => $model->getAttributes()
            ]);
        });
        
        static::saving(function ($model) {
            Log::debug('Salvando registro AtividadeSocioemocional', [
                'dados' => $model->toArray(),
                'observacoes' => $model->observacoes,
                'isDirty_observacoes' => $model->isDirty('observacoes'),
                'original_observacoes' => $model->getOriginal('observacoes', 'N/A')
            ]);
        });
    }
}
