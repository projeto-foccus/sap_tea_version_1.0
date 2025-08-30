<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PersonalidadeAluno extends Model
{
    protected $table = 'personalidade';
    protected $primaryKey = 'id_personalidade';
    protected $fillable = [
        'carac_principal',
        'inter_princ_carac',
        'livre_gosta_fazer',
        'feliz_est',
        'trist_est',
        'obj_apego',
        'fk_id_aluno'
    ];
    
    public $timestamps = false;
}
