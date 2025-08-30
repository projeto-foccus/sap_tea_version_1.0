<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropostaIntSoc extends Model
{
    protected $table = 'proposta_int_soc';
    protected $primaryKey = 'id_pro_int_soc';
    public $timestamps = false;
    protected $fillable = [
        'cod_pro_int_soc',
        'desc_pro_int_soc',
    ];
}
