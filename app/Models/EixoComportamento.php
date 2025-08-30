<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EixoComportamento extends Model
{
    public $timestamps = false;
    protected $table = 'eixo_comportamento';
    protected $fillable = ['ecp01','ecp02','ecp03','ecp04','ecp05','ecp06','ecp07','ecp08','ecp09','ecp10',
                            'ecp11','ecp12','ecp13','ecp14','ecp15','ecp16','ecp17','fk_alu_id_ecomp','data_insert_comportamento' ,
                            'fase_inv_comportamento'        
                            ];
}
