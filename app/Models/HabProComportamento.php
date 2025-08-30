<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HabProComportamento extends Model
{
    protected $table = 'hab_pro_comportamento';
    protected $primaryKey = 'id_hab_pro_comportamento'; // ajuste se necessário
    public $timestamps = false;
    protected $fillable = [
        'fk_id_hab_comportamento',
        'fk_id_pro_comportamento'
    ];
}
