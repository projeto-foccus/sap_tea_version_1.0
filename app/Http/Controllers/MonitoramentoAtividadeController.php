<?php

namespace App\Http\Controllers;

use App\Models\AtividadeComunicacao;
use App\Models\AtividadeComportamento;
use App\Models\AtividadeSocioemocional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class MonitoramentoAtividadeController extends Controller
{
    /**
     * Salva os dados de monitoramento de uma ou mais atividades.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function salvar(Request $request)
    {
        // Envolve toda a lógica em um bloco try-catch para capturar qualquer erro inesperado.
        try {
            // 1. Validação inicial para garantir que o aluno_id está presente e é um inteiro.
            $validator = Validator::make($request->all(), [
                'aluno_id' => 'required|integer',
            ]);

            // Se a validação falhar, retorna um erro claro.
            if ($validator->fails()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'O ID do aluno é obrigatório.'
                ], 422); // 422 Unprocessable Entity
            }

            $aluno_id = $request->input('aluno_id');
            $eixos = ['com_lin', 'comportamento', 'int_socio'];
            $linhasSalvas = 0;

            // 2. Itera sobre os possíveis eixos de atividade.
            foreach ($eixos as $eixo) {
                if ($request->has($eixo)) {
                    // Decodifica o JSON enviado pelo frontend.
                    $dadosEixo = json_decode($request->input($eixo), true);

                    if (is_array($dadosEixo)) {
                        foreach ($dadosEixo as $dadosLinha) {
                            // 3. Validação dos dados de cada linha.
                            if (empty($dadosLinha['data_inicial']) || ($dadosLinha['sim_inicial'] == 0 && $dadosLinha['nao_inicial'] == 0)) {
                                continue;
                            }

                            $modelClass = null;
                            switch ($eixo) {
                                case 'com_lin':
                                    $modelClass = AtividadeComunicacao::class;
                                    break;
                                case 'comportamento':
                                    $modelClass = AtividadeComportamento::class;
                                    break;
                                case 'int_socio':
                                    $modelClass = AtividadeSocioemocional::class;
                                    break;
                            }

                            if ($modelClass) {
                                // Usa o valor do flag enviado pelo frontend e garante que seja inteiro
                                $flag = isset($dadosLinha['flag']) ? (int)$dadosLinha['flag'] : ($dadosLinha['sim_inicial'] == 1 ? 2 : 1);

                                // Log detalhado para depuração
                                Log::info('Tentando salvar dados do eixo: ' . $eixo, [
                                    'aluno_id' => $aluno_id,
                                    'cod_atividade' => $dadosLinha['cod_atividade'] ?? 'Não informado',
                                    'data_monitoramento' => $dadosLinha['data_inicial'] ?? 'Não informada',
                                    'flag' => $flag,
                                    'fase_cadastro' => $dadosLinha['fase_cadastro'] ?? 'In',
                                    'sim_inicial' => $dadosLinha['sim_inicial'] ?? 'Não informado',
                                    'model' => $modelClass
                                ]);
                                
                                try {
                                    $modelClass::updateOrCreate(
                                        [
                                            'aluno_id' => $aluno_id,
                                            'cod_atividade' => $dadosLinha['cod_atividade'],
                                            'data_monitoramento' => $dadosLinha['data_inicial'], // valor do JS
                                            'flag' => $flag,
                                            'fase_cadastro' => $dadosLinha['fase_cadastro'] ?? 'In'
                                        ],
                                        [
                                            'flag' => $flag,
                                            'observacoes' => $dadosLinha['observacoes'] ?? '',
                                            'registro_timestamp' => round(microtime(true) * 1000),
                                            'fase_cadastro' => $dadosLinha['fase_cadastro'] ?? 'In',
                                            'realizado' => $dadosLinha['sim_inicial'] ?? null,
                                            'data_aplicacao' => $dadosLinha['data_inicial'], // Garantir que data_aplicacao também seja preenchida
                                            'data_monitoramento' => $dadosLinha['data_inicial']
                                        ]
                                    );
                                    Log::info('Dados salvos com sucesso para o eixo: ' . $eixo);
                                } catch (\Exception $e) {
                                    Log::error('Erro ao salvar dados do eixo ' . $eixo . ': ' . $e->getMessage(), [
                                        'exception' => $e,
                                        'aluno_id' => $aluno_id,
                                        'cod_atividade' => $dadosLinha['cod_atividade'] ?? 'Não informado'
                                    ]);
                                }
                                $linhasSalvas++;
                            }
                        }
                    }
                }
            }

            // 5. Retorna a resposta final baseada no número de linhas salvas.
            if ($linhasSalvas > 0) {
                return response()->json(['success' => true, 'message' => $linhasSalvas . ' atividade(s) salva(s) com sucesso!']);
            } else {
                return response()->json(['success' => false, 'message' => 'Nenhuma atividade válida para salvar. Verifique se a data e as opções (Sim/Não) foram preenchidas.']);
            }

        } catch (Throwable $e) {
            // 6. Captura QUALQUER erro que ocorrer (de sintaxe, de banco de dados, etc.).
            Log::error('Erro Crítico ao Salvar Monitoramento: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // Retorna um erro 500 com uma mensagem clara para o frontend.
            return response()->json([
                'success' => false,
                'message' => 'Ocorreu um erro inesperado no servidor. Por favor, contate o suporte. Detalhes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exibe o formulário do Indicativo Inicial para um aluno específico.
     */
    public function indicativoInicial($id)
    {
        // Busca detalhes do aluno
        $alunoDetalhado = \App\Models\Aluno::getAlunosDetalhados($id);

        // Comunicação/Linguagem: atividades realizadas e habilidades encontradas
        $comunicacao_atividades_realizadas = collect();
        $comunicacao_habilidades_encontradas = collect();
        // Comportamento
        $comportamento_agrupado = collect();
        // Socioemocional
        $socioemocional_agrupado = collect();

        // Exemplo de preenchimento (ajuste conforme modelo real)
        // Aqui você pode buscar os dados reais do banco, se necessário

        return view('rotina_monitoramento.IndicativoInicial', [
            'alunoDetalhado' => $alunoDetalhado,
            'comunicacao_atividades_realizadas' => $comunicacao_atividades_realizadas,
            'comunicacao_habilidades_encontradas' => $comunicacao_habilidades_encontradas,
            'comportamento_agrupado' => $comportamento_agrupado,
            'socioemocional_agrupado' => $socioemocional_agrupado,
        ]);
    }
    
    /**
     * Busca as atividades já cadastradas para um aluno específico.
     * Este método é usado para exibir as atividades já cadastradas em modo consulta.
     *
     * @param int $aluno_id ID do aluno
     * @return \Illuminate\Http\JsonResponse
     */
    public function buscarAtividadesCadastradas($aluno_id)
    {
        try {
            // Validar o ID do aluno
            if (!$aluno_id || !is_numeric($aluno_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID do aluno inválido.'
                ], 422);
            }
            
            // Buscar atividades cadastradas para cada eixo
            $atividadesComunicacao = AtividadeComunicacao::where('aluno_id', $aluno_id)
                ->where('fase_cadastro', 'In')
                ->get()
                ->map(function($item) {
                    return [
                        'cod_atividade' => $item->cod_atividade,
                        'data_monitoramento' => $item->data_monitoramento,
                        'data_aplicacao' => $item->data_aplicacao,
                        'realizado' => $item->realizado,
                        'observacoes' => $item->observacoes,
                        'flag' => $item->flag,
                        'registro_timestamp' => $item->registro_timestamp
                    ];
                });
                
            $atividadesComportamento = AtividadeComportamento::where('aluno_id', $aluno_id)
                ->where('fase_cadastro', 'In')
                ->get()
                ->map(function($item) {
                    return [
                        'cod_atividade' => $item->cod_atividade,
                        'data_monitoramento' => $item->data_monitoramento,
                        'data_aplicacao' => $item->data_aplicacao,
                        'realizado' => $item->realizado,
                        'observacoes' => $item->observacoes,
                        'flag' => $item->flag,
                        'registro_timestamp' => $item->registro_timestamp
                    ];
                });
                
            $atividadesSocioemocional = AtividadeSocioemocional::where('aluno_id', $aluno_id)
                ->where('fase_cadastro', 'In')
                ->get()
                ->map(function($item) {
                    return [
                        'cod_atividade' => $item->cod_atividade,
                        'data_monitoramento' => $item->data_monitoramento,
                        'data_aplicacao' => $item->data_aplicacao,
                        'realizado' => $item->realizado,
                        'observacoes' => $item->observacoes,
                        'flag' => $item->flag,
                        'registro_timestamp' => $item->registro_timestamp
                    ];
                });
            
            // Retornar os dados estruturados
            return response()->json([
                'success' => true,
                'data' => [
                    'com_lin' => $atividadesComunicacao,
                    'comportamento' => $atividadesComportamento,
                    'int_socio' => $atividadesSocioemocional
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao buscar atividades cadastradas: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Ocorreu um erro ao buscar as atividades cadastradas: ' . $e->getMessage()
            ], 500);
        }
    }
}
