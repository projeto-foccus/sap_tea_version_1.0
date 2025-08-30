<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResultEixoComportamento extends Model
{
    public function proposta()
    {
        return $this->belongsTo(PropostaComportamento::class, 'fk_id_pro_comportamento', 'id_pro_comportamento');
    }

    protected $table = 'result_eixo_comportamento';
    protected $primaryKey = 'id_result_eixo_comportamento';
    public $timestamps = false;
    protected $fillable = [
        'fk_hab_pro_comportamento',
        'fk_id_pro_comportamento',
        'fk_result_alu_id_comportamento',
        'date_cadastro',
        'tipo_fase_comportamento',
        'flag'
    ];
}
