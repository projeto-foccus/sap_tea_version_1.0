<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EixoComunicacaoLinguagem extends Model
{
    public $timestamps = false;
    protected $table = 'eixo_com_lin';
    protected $primaryKey = 'id_eixo_com_lin';
    protected $fillable = ['ecm01','ecm02','ecm03','ecm04','ecm05','ecm06','ecm07','ecm08','ecm09','ecm10',
                            'ecm11','ecm12','ecm13','ecm14','ecm15','ecm16','ecm17','ecm18','ecm19','ecm20',
                            'ecm21','ecm22','ecm23','ecm24','ecm25','ecm26','ecm27','ecm28','ecm29','ecm30',
                            'ecm31','ecm32','fk_alu_id_ecomling','data_insert_com_lin','fase_inv_com_lin'
                          ];
}