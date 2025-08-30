<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Escola extends Model
{
    public function orgao()
    {
        return $this->belongsTo(\App\Models\Orgao::class, 'fk_org_esc_id', 'org_id');
    }

    protected $table = 'escola';
    protected $primaryKey = 'esc_id';
    public $timestamps = false;

    // Se precisar de fillable para mass assignment
    protected $fillable = [
        'esc_dtcad',
        'esc_inep',
        'esc_cnpj',
        'esc_razao_social',
        'esc_endereco',
        'esc_bairro',
        'esc_municipio',
        'esc_cep',
        'esc_uf',
        'esc_telefone',
        'esc_email',
        'fk_org_esc_id',
    ];

    // Relacionamento inverso: turmas desta escola (opcional)
    // public function turmas()
    // {
    //     return $this->hasMany(Turma::class, 'fk_inep', 'esc_inep');
    // }
}
