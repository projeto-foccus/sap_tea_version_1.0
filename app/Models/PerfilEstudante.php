<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Models\Aluno;

class PerfilEstudante extends Model
{
    /**
     * Nome da tabela no banco de dados
     *
     * @var string
     */
    protected $table = 'perfil_estudante';

    /**
     * Chave primária da tabela
     * 
     * @var string
     */
    protected $primaryKey = 'id_perfil';

    /**
     * Campos que podem ser preenchidos em massa
     *
     * @var array
     */
    protected $fillable = [
        'diag_laudo', 
        'data_laudo',
        'cid', 
        'nome_medico',
        'nivel_suporte', 
        'uso_medicamento', 
        'quais_medicamento', 
        'nec_pro_apoio',
        'prof_apoio',
        'loc_01', 
        'hig_02', 
        'ali_03', 
        'com_04', 
        'out_05', 
        'out_momentos', 
        'at_especializado',
        'fk_id_aluno'
    ];

    /**
     * Indica se o modelo deve ser timestamped
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Relacionamento com o modelo Aluno
     */
    public function aluno()
    {
        return $this->belongsTo(Aluno::class, 'fk_id_aluno', 'alu_id');
    }

    /**
     * Cria ou atualiza um perfil do estudante
     *
     * @param array $dados
     * @return self
     * @throws \Exception
     */
    public static function criarPerfil(array $dados, $atualizarExistente = false)
    {
        try {
            Log::info('Tentando criar/atualizar perfil do estudante', ['dados' => $dados]);
            
            // Verifica se o aluno existe
            $aluno = Aluno::find($dados['fk_id_aluno']);
            if (!$aluno) {
                throw new \Exception('Aluno não encontrado com o ID: ' . $dados['fk_id_aluno']);
            }
            
            // Verifica se já existe um perfil para este aluno
            $perfil = self::where('fk_id_aluno', $dados['fk_id_aluno'])->first();
            
            if ($perfil) {
                if (!$atualizarExistente) {
                    throw new \Exception('Já existe um perfil cadastrado para este aluno');
                }
                // Atualiza o perfil existente
                Log::info('Atualizando perfil existente', ['id_perfil' => $perfil->id_perfil]);
                $perfil->fill($dados);
                $perfil->save();
                Log::info('Perfil atualizado com sucesso', ['id_perfil' => $perfil->id_perfil]);
                return $perfil;
            }
            
            // Cria um novo perfil
            $perfil = new self();
            $perfil->fill($dados);
            $perfil->save();
            
            Log::info('Perfil criado com sucesso', ['id_perfil' => $perfil->id_perfil]);
            
            return $perfil;
            
        } catch (\Exception $e) {
            Log::error('Erro ao criar/atualizar perfil do estudante: ' . $e->getMessage(), [
                'dados' => $dados,
                'erro' => [
                    'mensagem' => $e->getMessage(),
                    'arquivo' => $e->getFile(),
                    'linha' => $e->getLine(),
                    'codigo' => $e->getCode(),
                    'trace' => $e->getTraceAsString()
                ]
            ]);
            throw $e;
        }
    }



   
}

