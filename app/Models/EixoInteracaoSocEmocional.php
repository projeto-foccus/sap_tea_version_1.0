<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EixoInteracaoSocEmocional extends Model
{
    public $timestamps = false;
    protected $table = 'eixo_int_socio';
    protected $primaryKey = 'id_eixo_int_socio';
    protected $fillable = ['eis01','eis02','eis03','eis04','eis05','eis06','eis07','eis08','eis09','eis10',
    'eis11','eis12','eis13','eis14','eis15','eis16','eis17','eis18','fk_alu_id_eintsoc', 'data_insert_int_socio',
    'fase_inv_int_socio'
];
}
