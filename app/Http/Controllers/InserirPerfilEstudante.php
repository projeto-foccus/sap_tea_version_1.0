<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PerfilEstudante;
use App\Models\PersonalidadeAluno;
use App\Models\Comunicacao;
use App\Models\Preferencia;
use App\Models\PerfilFamilia;
use App\Models\PerfilProfissional;
use App\Models\Aluno;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class InserirPerfilEstudante extends Controller
{
    /**
     * Valida os dados do formulário de perfil do estudante
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    protected function validarDados(Request $request)
    {
        try {
            // Log dos dados recebidos para depuração
            Log::info('Dados recebidos para validação:', [
                'request_all' => $request->all(),
                'request_method' => $request->method(),
                'is_ajax' => $request->ajax(),
                'has_aluno_id' => $request->has('aluno_id')
            ]);
            
            // Define valores padrão para campos opcionais
            $dadosPadrao = [
                'diag_laudo' => 0,
                'nivel_suporte' => 1,
                'uso_medicamento' => 0,
                'nec_pro_apoio' => 0,
                'conta_pro_apoio' => 0,
            ];

            // Valida os dados fornecidos com regras mais flexíveis
            $dados = $request->validate([
                'aluno_id' => 'required|integer|exists:aluno,alu_id',
                'diag_laudo' => 'nullable|in:0,1',
                'data_laudo' => 'nullable|date',
                'cid' => 'nullable|string|max:20',
                'nome_medico' => 'nullable|string|max:255',
                'nivel_suporte' => 'nullable|in:1,2,3',
                'uso_medicamento' => 'nullable|in:0,1',
                'quais_medicamento' => 'nullable|string|max:255',
                'nec_pro_apoio' => 'nullable|in:0,1',
                'conta_pro_apoio' => 'nullable|in:0,1',
                'at_especializado' => 'nullable|string|max:255',
                'outros' => 'nullable|string|max:255',
                'locomocao' => 'nullable|in:on',
                'higiene' => 'nullable|in:on',
                'alimentacao' => 'nullable|in:on',
                'comunicacao' => 'nullable|in:on',
            ]);

            // Aplica valores padrão para campos não fornecidos
            foreach ($dadosPadrao as $campo => $valor) {
                if (!isset($dados[$campo]) || $dados[$campo] === null) {
                    $dados[$campo] = $valor;
                }
            }

            Log::info('Dados validados com sucesso:', ['dados' => $dados]);
            return $dados;
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Erro de validação:', [
                'errors' => $e->errors(),
                'request' => $request->all()
            ]);
            
            // Retorna uma resposta JSON com os erros de validação
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação dos dados',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erro inesperado na validação:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao validar os dados: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Processa os dados do formulário e salva no banco de dados
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function inserir_perfil_estudante(Request $request)
    {
        try {
            // Log da requisição recebida
            Log::info('Recebida requisição para inserir/atualizar perfil do estudante', [
                'method' => $request->method(),
                'ajax' => $request->ajax(),
                'content_type' => $request->header('Content-Type'),
                'has_aluno_id' => $request->has('aluno_id'),
                'all_data' => $request->all()
            ]);
            
            // Valida os dados do formulário
            $dados = $this->validarDados($request);
            
            // Se a validação retornou uma resposta JSON (erro), retorna-a diretamente
            if ($dados instanceof \Illuminate\Http\JsonResponse) {
                return $dados;
            }
            
            $alunoId = $dados['aluno_id'];
            Log::info('Dados validados com sucesso', ['aluno_id' => $alunoId]);

            // Verifica se já existe um perfil para este aluno
            $perfilExistente = PerfilEstudante::where('fk_id_aluno', $alunoId)->first();
            
            // Se existir, atualiza em vez de retornar erro
            if ($perfilExistente) {
                Log::info('Atualizando perfil existente para o aluno', ['aluno_id' => $alunoId, 'perfil_id' => $perfilExistente->id_perfil]);
            } else {
                Log::info('Criando novo perfil para o aluno', ['aluno_id' => $alunoId]);
            }

            // Verifica se o aluno existe
            $aluno = Aluno::find($alunoId);
            if (!$aluno) {
                Log::warning('Aluno não encontrado', ['aluno_id' => $alunoId]);
                return response()->json([
                    'success' => false,
                    'message' => 'Aluno não encontrado com o ID: ' . $alunoId
                ], 404);
            }

            // Processa os checkboxes com valores padrão
            $dadosPerfil = [
                'diag_laudo' => $dados['diag_laudo'] ?? 0,
                'data_laudo' => $dados['data_laudo'] ?? null,
                'cid' => $dados['cid'] ?? null,
                'nome_medico' => $dados['nome_medico'] ?? null,
                'nivel_suporte' => $dados['nivel_suporte'] ?? 1,
                'uso_medicamento' => $dados['uso_medicamento'] ?? 0,
                'quais_medicamento' => $dados['quais_medicamento'] ?? null,
                'nec_pro_apoio' => $dados['nec_pro_apoio'] ?? 0,
                'prof_apoio' => $dados['conta_pro_apoio'] ?? 0,
                'loc_01' => $request->has('locomocao') ? 1 : 0,
                'hig_02' => $request->has('higiene') ? 1 : 0,
                'ali_03' => $request->has('alimentacao') ? 1 : 0,
                'com_04' => $request->has('comunicacao') ? 1 : 0,
                'out_05' => $request->has('outros') ? 1 : 0,
                'out_momentos' => $request->input('outros_momentos_apoio'),
                'at_especializado' => $dados['at_especializado'] ?? null,
                'fk_id_aluno' => $alunoId
            ];
         
            // Inicia a transação
            DB::beginTransaction();

            try {
                // Cria ou atualiza o perfil do estudante
                $perfilExistente = PerfilEstudante::where('fk_id_aluno', $alunoId)->first();
                $perfilEstudante = PerfilEstudante::criarPerfil($dadosPerfil, true);

                // Atualiza a flag do aluno
                $aluno->flag_perfil = '*';
                $aluno->save();
                
                // Se estivermos atualizando um perfil existente, removemos os registros antigos
                if ($perfilExistente) {
                    // Removemos os registros antigos de comunicação, preferência e personalidade
                    // Eles serão recriados nos métodos específicos
                    \App\Models\Comunicacao::where('fk_id_aluno', $alunoId)->delete();
                    \App\Models\Preferencia::where('fk_id_aluno', $alunoId)->delete();
                    \App\Models\PersonalidadeAluno::where('fk_id_aluno', $alunoId)->delete();
                    
                    Log::info('Registros antigos removidos para atualização', [
                        'aluno_id' => $alunoId
                    ]);
                }

                // Tenta salvar os profissionais se necessário
                try {
                    if (($dados['nec_pro_apoio'] ?? 0) == 1) {
                        $this->salvarProfissionais($request, $alunoId);
                    }
                } catch (\Exception $e) {
                    Log::warning('Erro ao salvar profissionais, mas continuando o processo', [
                        'erro' => $e->getMessage(),
                        'aluno_id' => $alunoId
                    ]);
                }
                
                // Salva os dados de comunicação
                try {
                    $this->salvarComunicacao($request, $alunoId);
                } catch (\Exception $e) {
                    Log::warning('Erro ao salvar comunicação, mas continuando o processo', [
                        'erro' => $e->getMessage(),
                        'aluno_id' => $alunoId
                    ]);
                }
                
                // Salva os dados de preferência
                try {
                    $this->salvarPreferencia($request, $alunoId);
                } catch (\Exception $e) {
                    Log::warning('Erro ao salvar preferências, mas continuando o processo', [
                        'erro' => $e->getMessage(),
                        'aluno_id' => $alunoId
                    ]);
                }
                
                // Salva os dados de personalidade
                try {
                    $this->salvarPersonalidade($request, $alunoId);
                } catch (\Exception $e) {
                    Log::warning('Erro ao salvar personalidade, mas continuando o processo', [
                        'erro' => $e->getMessage(),
                        'aluno_id' => $alunoId
                    ]);
                }
                
                // Salva os dados do perfil da família
                try {
                    $this->salvarPerfilFamilia($request, $alunoId);
                } catch (\Exception $e) {
                    Log::warning('Erro ao salvar perfil da família, mas continuando o processo', [
                        'erro' => $e->getMessage(),
                        'aluno_id' => $alunoId
                    ]);
                }

                // Confirma a transação
                DB::commit();

                Log::info('Perfil do estudante criado com sucesso', [
                    'perfil_id' => $perfilEstudante->id_perfil,
                    'aluno_id' => $alunoId
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Perfil do estudante criado com sucesso!',
                    'perfil' => $perfilEstudante
                ]);

            } catch (\Exception $e) {
                // Desfaz a transação em caso de erro
                DB::rollBack();
                
                // Registra o erro completo no log
                $errorMessage = 'Erro ao criar perfil do estudante: ' . $e->getMessage();
                $errorContext = [
                    'aluno_id' => $alunoId,
                    'dados' => $dadosPerfil,
                    'erro' => [
                        'mensagem' => $e->getMessage(),
                        'arquivo' => $e->getFile(),
                        'linha' => $e->getLine(),
                        'codigo' => $e->getCode(),
                        'trace' => $e->getTraceAsString()
                    ]
                ];
                
                Log::error($errorMessage, $errorContext);

                // Retorna uma mensagem genérica para o usuário, mas com um ID de rastreamento
                $errorId = uniqid('ERR', true);
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao processar a solicitação. Código: ' . $errorId,
                    'error_id' => $errorId
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Erro na requisição de inserção de perfil: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar a requisição. Por favor, tente novamente.'
            ], 500);
        }
    }
    
    /**
     * Salva os profissionais do aluno
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $alunoId
     * @return void
     * @throws \Exception
     */
    private function salvarProfissionais($request, $alunoId)
    {
        try {
            // Remove profissionais existentes
            $removidos = PerfilProfissional::where('fk_id_aluno', $alunoId)->delete();
            
            Log::info('Profissionais removidos', [
                'quantidade' => $removidos,
                'aluno_id' => $alunoId
            ]);
            
            $profissionaisSalvos = 0;
            
            // Processa cada linha de profissional (01 a 05)
            for ($i = 1; $i <= 5; $i++) {
                $numero = str_pad($i, 2, '0', STR_PAD_LEFT);
                $nome = trim($request->input("nome_profissional_{$numero}", ''));
                
                // Só processa se o nome estiver preenchido
                if (!empty($nome)) {
                    $dadosProfissional = [
                        'nome_profissional' => $nome,
                        'especialidade_profissional' => $request->input("especialidade_profissional_{$numero}", null),
                        'observacoes_profissional' => $request->input("observacoes_profissional_{$numero}", null),
                        'fk_id_aluno' => $alunoId,
                        'data_cadastro_profissional' => now()->toDateString()
                    ];
                    
                    // Valida os dados antes de salvar
                    $validador = \Validator::make($dadosProfissional, [
                        'nome_profissional' => 'required|string|max:255',
                        'especialidade_profissional' => 'nullable|string|max:255',
                        'observacoes_profissional' => 'nullable|string',
                        'fk_id_aluno' => 'required|integer|exists:aluno,alu_id',
                        'data_cadastro_profissional' => 'required|date'
                    ]);
                    
                    if ($validador->fails()) {
                        Log::warning('Dados inválidos para profissional', [
                            'erros' => $validador->errors(),
                            'dados' => $dadosProfissional
                        ]);
                        continue; // Pula para o próximo profissional em caso de erro
                    }
                    
                    // Cria o registro do profissional
                    PerfilProfissional::create($dadosProfissional);
                    $profissionaisSalvos++;
                }
            }
            
            Log::info('Profissionais salvos com sucesso', [
                'quantidade' => $profissionaisSalvos,
                'aluno_id' => $alunoId
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao salvar profissionais', [
                'erro' => $e->getMessage(),
                'aluno_id' => $alunoId,
                'trace' => $e->getTraceAsString()
            ]);
            // Não lança a exceção para não interromper o fluxo principal
            // Apenas registra o erro no log
        }
    }
    
    /**
     * Salva os dados de preferência do aluno
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $alunoId
     * @return void
     */
    private function salvarPreferencia($request, $alunoId)
    {
        try {
            // Remove preferência existente
            \App\Models\Preferencia::where('fk_id_aluno', $alunoId)->delete();
            
            // Prepara os dados
            $dadosPreferencia = [
                'auditivo_04' => $request->input('auditivo_04', 0),
                'visual_04' => $request->input('visual_04', 0),
                'tatil_04' => $request->input('tatil_04', 0),
                'outros_04' => $request->input('outros_04', 0),
                'maneja_04' => $request->input('maneja_04'),
                'asa_04' => $request->input('asa_04', 0),
                'alimentos_pref_04' => $request->input('alimentos_pref_04'),
                'alimento_evita_04' => $request->input('alimento_evita_04'),
                'contato_pc_04' => $request->input('contato_pc_04'),
                'reage_contato' => $request->input('reage_contato'),
                'interacao_escola_04' => $request->input('interacao_escola_04'),
                'interesse_atividade_04' => $request->input('interesse_atividade_04'),
                'aprende_visual_04' => $request->input('aprende_visual_04', 0),
                'recurso_auditivo_04' => $request->input('recurso_auditivo_04', 0),
                'material_concreto_04' => $request->input('material_concreto_04', 0),
                'outro_identificar_04' => $request->input('outro_identificar_04', 0),
                'descricao_outro_identificar_04' => $request->input('descricao_outro_identificar_04'),
                'realiza_tarefa_04' => $request->input('realiza_tarefa_04'),
                'mostram_eficazes_04' => $request->input('mostram_eficazes_04'),
                'prefere_ts_04' => $request->input('prefere_ts_04'),
                'fk_id_aluno' => $alunoId
            ];
            
            // Valida os dados
            $validador = \Validator::make($dadosPreferencia, [
                'auditivo_04' => 'nullable|integer',
                'visual_04' => 'nullable|integer',
                'tatil_04' => 'nullable|integer',
                'outros_04' => 'nullable|integer',
                'maneja_04' => 'nullable|string|max:255',
                'asa_04' => 'nullable|integer',
                'alimentos_pref_04' => 'nullable|string',
                'alimento_evita_04' => 'nullable|string',
                'contato_pc_04' => 'nullable|string|max:255',
                'reage_contato' => 'nullable|string|max:255',
                'interacao_escola_04' => 'nullable|string',
                'interesse_atividade_04' => 'nullable|string',
                'aprende_visual_04' => 'nullable|integer',
                'recurso_auditivo_04' => 'nullable|integer',
                'material_concreto_04' => 'nullable|integer',
                'outro_identificar_04' => 'nullable|integer',
                'descricao_outro_identificar_04' => 'nullable|string|max:255',
                'realiza_tarefa_04' => 'nullable|string',
                'mostram_eficazes_04' => 'nullable|string',
                'prefere_ts_04' => 'nullable|string|max:255',
                'fk_id_aluno' => 'required|integer|exists:aluno,alu_id'
            ]);
            
            if ($validador->fails()) {
                Log::warning('Dados inválidos para preferência', [
                    'erros' => $validador->errors(),
                    'dados' => $dadosPreferencia
                ]);
                return;
            }
            
            // Cria o registro de preferência
            \App\Models\Preferencia::create($dadosPreferencia);
            
            Log::info('Dados de preferência salvos com sucesso', [
                'aluno_id' => $alunoId
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao salvar dados de preferência', [
                'erro' => $e->getMessage(),
                'aluno_id' => $alunoId,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
    
    /**
     * Salva os dados de personalidade do aluno
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $alunoId
     * @return void
     */
    /**
     * Salva os dados de comunicação do aluno
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $alunoId
     * @return void
     */
    private function salvarComunicacao($request, $alunoId)
    {
        try {
            // Remove comunicação existente
            \App\Models\Comunicacao::where('fk_id_aluno', $alunoId)->delete();
            
            // Prepara os dados
            $dadosComunicacao = [
                'como_comunica' => $request->input('como_comunica'),
                'compreende_ordens' => $request->input('compreende_ordens'),
                'comunica_necessidades' => $request->input('comunica_necessidades'),
                'comunica_dor' => $request->input('comunica_dor'),
                'comunica_forma' => $request->input('comunica_forma'),
                'comunica_recados' => $request->input('comunica_recados'),
                'comunica_escolha' => $request->input('comunica_escolha'),
                'comunica_nao_verbal' => $request->input('comunica_nao_verbal'),
                'comunica_gestos' => $request->input('comunica_gestos'),
                'comunica_expressoes' => $request->input('comunica_expressoes'),
                'comunica_sons' => $request->input('comunica_sons'),
                'comunica_palavras' => $request->input('comunica_palavras'),
                'comunica_frases' => $request->input('comunica_frases'),
                'comunica_outros' => $request->input('comunica_outros'),
                'comunica_outros_descricao' => $request->input('comunica_outros_descricao'),
                'fk_id_aluno' => $alunoId
            ];
            
            // Valida os dados
            $validador = \Validator::make($dadosComunicacao, [
                'como_comunica' => 'nullable|string|max:255',
                'compreende_ordens' => 'nullable|string|max:255',
                'comunica_necessidades' => 'nullable|string|max:255',
                'comunica_dor' => 'nullable|string|max:255',
                'comunica_forma' => 'nullable|string|max:255',
                'comunica_recados' => 'nullable|string|max:255',
                'comunica_escolha' => 'nullable|string|max:255',
                'comunica_nao_verbal' => 'nullable|integer',
                'comunica_gestos' => 'nullable|integer',
                'comunica_expressoes' => 'nullable|integer',
                'comunica_sons' => 'nullable|integer',
                'comunica_palavras' => 'nullable|integer',
                'comunica_frases' => 'nullable|integer',
                'comunica_outros' => 'nullable|integer',
                'comunica_outros_descricao' => 'nullable|string|max:255',
                'fk_id_aluno' => 'required|integer|exists:aluno,alu_id'
            ]);
            
            if ($validador->fails()) {
                Log::warning('Dados inválidos para comunicação', [
                    'erros' => $validador->errors(),
                    'dados' => $dadosComunicacao
                ]);
                return;
            }
            
            // Cria o registro de comunicação
            \App\Models\Comunicacao::create($dadosComunicacao);
            
            Log::info('Dados de comunicação salvos com sucesso', [
                'aluno_id' => $alunoId
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao salvar dados de comunicação', [
                'erro' => $e->getMessage(),
                'aluno_id' => $alunoId,
                'trace' => $e->getTraceAsString()
            ]);
            // Não lança a exceção para não interromper o fluxo principal
            // Apenas registra o erro no log
        }
    }
    
    /**
     * Salva os dados de personalidade do aluno
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $alunoId
     * @return void
     */
    private function salvarPersonalidade($request, $alunoId)
    {
        try {
            // Remove personalidade existente
            \App\Models\PersonalidadeAluno::where('fk_id_aluno', $alunoId)->delete();
            
            // Prepara os dados
            $dadosPersonalidade = [
                'carac_principal' => $request->input('principais_caracteristicas'),
                'inter_princ_carac' => $request->input('areas_interesse'),
                'livre_gosta_fazer' => $request->input('atividades_livre'),
                'feliz_est' => $request->input('feliz'),
                'trist_est' => $request->input('triste'),
                'obj_apego' => $request->input('objeto_apego'),
                'fk_id_aluno' => $alunoId
            ];
            
            // Cria o registro de personalidade
            \App\Models\PersonalidadeAluno::create($dadosPersonalidade);
            
            Log::info('Dados de personalidade salvos com sucesso', [
                'aluno_id' => $alunoId
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao salvar dados de personalidade', [
                'erro' => $e->getMessage(),
                'aluno_id' => $alunoId,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
    
    /**
     * Salva os dados do perfil da família do aluno
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $alunoId
     * @return void
     */
    private function salvarPerfilFamilia($request, $alunoId)
    {
        try {
            // Remove perfil de família existente
            \App\Models\PerfilFamilia::where('fk_id_aluno', $alunoId)->delete();
            
            // Prepara os dados
            $dadosFamilia = [
                'expectativa_05' => $request->input('expectativas_familia'),
                'estrategia_05' => $request->input('estrategias_familia'),
                'crise_esta_05' => $request->input('crise_estresse'),
                'fk_id_aluno' => $alunoId
            ];
            
            // Valida os dados
            $validador = \Validator::make($dadosFamilia, [
                'expectativa_05' => 'nullable|string',
                'estrategia_05' => 'nullable|string',
                'crise_esta_05' => 'nullable|string',
                'fk_id_aluno' => 'required|integer|exists:aluno,alu_id'
            ]);
            
            if ($validador->fails()) {
                Log::warning('Dados inválidos para perfil da família', [
                    'erros' => $validador->errors(),
                    'dados' => $dadosFamilia
                ]);
                return;
            }
            
            // Cria o registro de perfil da família
            \App\Models\PerfilFamilia::create($dadosFamilia);
            
            Log::info('Dados do perfil da família salvos com sucesso', ['aluno_id' => $alunoId]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao salvar perfil da família', [
                'erro' => $e->getMessage(),
                'aluno_id' => $alunoId,
                'trace' => $e->getTraceAsString()
            ]);
            // Não lança a exceção para não interromper o fluxo principal
            // Apenas registra o erro no log
        }
    }
}
