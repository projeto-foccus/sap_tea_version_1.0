<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HabProComLin extends Model
{
    protected $table = 'hab_pro_com_lin';
    protected $primaryKey = 'id_hab_pro_com_lin'; // ajuste se necessário
    public $timestamps = false;
    protected $fillable = [
        'fk_id_hab_com_lin',
        'fk_id_pro_com_lin'
    ];
}
