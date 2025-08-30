<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ControleFasesSondagem extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'controle_fases_sondagem';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_aluno',
        'ano',
        'fase_inicial',
        'cont_I',
        'fase_cont1',
        'cont_fase_c1',
        'fase_cont2',
        'cont_fase_c2',
        'fase_final',
        'cont_fase_final',
        'flag_inicial',
        'flag_c1',
        'flag_c2',
        'flag_final',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
