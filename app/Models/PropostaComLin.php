<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropostaComLin extends Model
{
    protected $table = 'proposta_com_lin';
    protected $primaryKey = 'id_pro_com_lin';
    public $timestamps = false;
    protected $fillable = [
        'cod_pro_com_lin',
        'desc_pro_com_lin',
    ];
}
