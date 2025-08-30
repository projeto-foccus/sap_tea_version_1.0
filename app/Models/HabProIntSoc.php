<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HabProIntSoc extends Model
{
    protected $table = 'hab_pro_int_soc';
    protected $primaryKey = 'id_hab_pro_int_soc'; // ajuste se necessário
    public $timestamps = false;
    protected $fillable = [
        'fk_id_hab_int_soc',
        'fk_id_pro_int_soc'
    ];
}
