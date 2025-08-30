<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreenchimentoInventario extends Model
{
    public $timestamps = false;
    protected $table = 'preenchimento_inventario';
    protected $primaryKey = 'id_preenchimento';
    protected $fillable = ['professor_responsavel','nivel_suporte','nivel_comunicacao',
                        'fase_inv_preenchimento','fk_id_aluno','data_cad_inventario'

    ];
}     
    
