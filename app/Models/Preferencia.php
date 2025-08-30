<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Preferencia extends Model
{
    protected $table = 'preferencia';
    protected $primaryKey = 'id_preferencia';

    protected $fillable=['auditivo_04','visual_04','tatil_04','outros_04','maneja_04',
'asa_04','alimentos_pref_04','alimento_evita_04',
'contato_pc_04','reage_contato','interacao_escola_04','interesse_atividade_04',
'aprende_visual_04','recurso_auditivo_04','material_concreto_04','outro_identificar_04',
'descricao_outro_identificar_04','prefere_ts_04','mostram_eficazes_04',
'realiza_tarefa_04','fk_id_aluno'
];
public $timestamps = false;
}
