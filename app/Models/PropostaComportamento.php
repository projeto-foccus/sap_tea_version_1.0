<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropostaComportamento extends Model
{
    protected $table = 'proposta_comportamento';
    protected $primaryKey = 'id_pro_comportamento';
    public $timestamps = false;
    protected $fillable = [
        'cod_pro_comportamento',
        'desc_pro_comportamento',
    ];
}
