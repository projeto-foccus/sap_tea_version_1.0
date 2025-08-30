<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResultEixoIntSocio extends Model
{
    public function proposta()
    {
        return $this->belongsTo(PropostaIntSoc::class, 'fk_id_pro_int_socio', 'id_pro_int_soc');
    }

    protected $table = 'result_eixo_int_socio';
    protected $primaryKey = 'id_result_eixo_int_socio';
    public $timestamps = false;
    protected $fillable = [
        'fk_hab_pro_int_socio',
        'fk_id_pro_int_socio',
        'fk_result_alu_id_int_socio',
        'date_cadastro',
        'tipo_fase_int_socio',
    ];
}
