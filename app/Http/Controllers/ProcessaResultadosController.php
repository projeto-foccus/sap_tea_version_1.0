<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\EixoComunicacaoLinguagem;
use App\Models\EixoComportamento;
use App\Models\EixoInteracaoSocEmocional;
use App\Models\HabProComLin;
use App\Models\HabProComportamento;
use App\Models\HabProIntSoc;
use App\Models\ResultEixoComLin;
use App\Models\ResultEixoComportamento;
use App\Models\ResultEixoIntSocio;
use Carbon\Carbon;

class ProcessaResultadosController extends Controller
{
    // Função para exibir o monitoramento do aluno (ajuste o nome conforme o seu controller)
    public function monitoramentoAluno(Request $request)
    {
        // Obter o ID do aluno da requisição
        $alunoId = $request->route('id');

        // Consultar dados do aluno
        $alunoDetalhado = \App\Models\Aluno::getAlunosDetalhados($alunoId);
        $aluno = $alunoDetalhado[0] ?? null;
        
        if (!$aluno) {
            return redirect()->back()->with('error', 'Aluno não encontrado!');
        }
        
        // Consulta agrupada Comunicação/Linguagem para o aluno específico
        $comunicacao_linguagem_agrupado = DB::select("
    SELECT 
      acl.desc_ati_com_lin AS atividade,
      hcl.desc_hab_com_lin AS habilidade
    FROM 
      cad_ativ_eixo_com_lin caecl
    INNER JOIN 
      atividade_com_lin acl ON caecl.cod_atividade = acl.cod_ati_com_lin
    LEFT JOIN 
      hab_pro_com_lin hpc ON acl.id_ati_com_lin = hpc.fk_id_pro_com_lin
    LEFT JOIN 
      habilidade_com_lin hcl ON hpc.fk_id_hab_com_lin = hcl.id_hab_com_lin
    GROUP BY 
      acl.id_ati_com_lin, hcl.id_hab_com_lin
    ORDER BY 
      acl.desc_ati_com_lin, hcl.desc_hab_com_lin
");
        // Consulta agrupada Comportamento - EXCLUINDO a atividade ECP03 (id=3) para o aluno específico
        $comportamento_agrupado = DB::select("
            SELECT 
                r.fk_id_pro_comportamento,
                r.fk_result_alu_id_comportamento,
                r.tipo_fase_comportamento,
                a.cod_ati_comportamento,
                a.desc_ati_comportamento,
                COUNT(*) AS total
            FROM 
                result_eixo_comportamento r
            JOIN 
                atividade_comportamento a ON r.fk_id_pro_comportamento = a.id_ati_comportamento
            WHERE
                r.fk_result_alu_id_comportamento = ? AND
                a.id_ati_comportamento != 3 AND 
                a.cod_ati_comportamento != 'ECP03'
            GROUP BY
                r.fk_id_pro_comportamento,
                r.fk_result_alu_id_comportamento,
                r.tipo_fase_comportamento,
                a.cod_ati_comportamento,
                a.desc_ati_comportamento
            ORDER BY
                COUNT(*) DESC
        ", [$alunoId]);
        // Consulta agrupada Interação Socioemocional - EXCLUINDO a atividade EIS01 (id=1) para o aluno específico
        $socioemocional_agrupado = DB::select("
            SELECT 
                r.fk_id_pro_int_socio,
                r.fk_result_alu_id_int_socio,
                r.tipo_fase_int_socio,
                a.cod_ati_int_soc,
                a.desc_ati_int_soc,
                a.id_ati_int_soc,
                COUNT(*) AS total
            FROM 
                result_eixo_int_soc r
            JOIN 
                atividade_int_soc a ON r.fk_id_pro_int_socio = a.id_ati_int_soc
            WHERE
                r.fk_result_alu_id_int_socio = ?
                AND a.id_ati_int_soc != 1
                AND a.cod_ati_int_soc != 'EIS01'
                AND r.fk_id_pro_int_socio != 1
            GROUP BY
                r.fk_id_pro_int_socio,
                r.fk_result_alu_id_int_socio,
                r.tipo_fase_int_socio,
                a.cod_ati_int_soc,
                a.desc_ati_int_soc,
                a.id_ati_int_soc
            ORDER BY
                COUNT(*) DESC
        ", [$alunoId]);

        // Garante arrays vazios se não houver dados
        $comunicacao_linguagem_agrupado = $comunicacao_linguagem_agrupado ?: [];
        $comportamento_agrupado = $comportamento_agrupado ?: [];
        $socioemocional_agrupado = $socioemocional_agrupado ?: [];

        // --- NOVO: calcular e passar as variáveis *_atividades_ordenadas ---
        // Comunicação/Linguagem (EIS01 aparece apenas uma vez, sem contagem de frequência)
        $comunicacao_frequencias = [];
        foreach ($comunicacao_linguagem_agrupado as $item) {
            $cod = $item->cod_ati_com_lin;
            $desc = $item->desc_ati_com_lin;
            // Se for EIS01, adiciona apenas uma vez e ignora a contagem
            if (strpos($cod, 'EIS01') === 0) {
                if (!isset($comunicacao_frequencias[$cod])) {
                    $comunicacao_frequencias[$cod] = [
                        'codigo' => $cod,
                        'descricao' => $desc,
                        'total' => 1 // Sempre 1
                    ];
                }
                continue;
            }
            // Para os demais, faz a contagem normalmente
            $total = $item->total;
            if (!isset($comunicacao_frequencias[$cod])) {
                $comunicacao_frequencias[$cod] = [
                    'codigo' => $cod,
                    'descricao' => $desc,
                    'total' => 0
                ];
            }
            $comunicacao_frequencias[$cod]['total'] += $total;
        }
        // Ordena por total desc
        usort($comunicacao_frequencias, function($a, $b) { return $b['total'] <=> $a['total']; });
        // Gera lista conforme frequência (EIS01 só entra uma vez)
        $comunicacao_atividades_ordenadas = [];
        foreach ($comunicacao_frequencias as $item) {
            $repeticoes = $item['total'];
            for ($i = 0; $i < $repeticoes; $i++) {
                $obj = new \stdClass();
                $obj->cod_ati_com_lin = $item['codigo'];
                $obj->desc_ati_com_lin = $item['descricao'];
                $comunicacao_atividades_ordenadas[] = $obj;
                // Se for EIS01, só adiciona uma vez
                if (strpos($item['codigo'], 'EIS01') === 0) {
                    break;
                }
            }
        }

        // Comportamento - IMPORTANTE: Excluir COMPLETAMENTE a atividade id=3 e código ECP03 de todas as contagens e resumos
        $comportamento_frequencias = [];
        foreach ($comportamento_agrupado as $item) {
            // Filtro mais abrangente: exclui a atividade ECP03 (id=3) de todas as contagens
            // Verifica se é a atividade com id=3 OU código ECP03
            if (
                (isset($item->id_ati_comportamento) && $item->id_ati_comportamento == 3) ||
                (isset($item->cod_ati_comportamento) && $item->cod_ati_comportamento === 'ECP03')
            ) {
                // Pula completamente esta atividade
                continue;
            }
            
            $cod = $item->cod_ati_comportamento;
            $desc = $item->desc_ati_comportamento;
            $total = $item->total;
            
            // Verifica novamente pelo código para garantir que não seja ECP03
            if (strpos($cod, 'ECP03') === 0) {
                continue;
            }
            
            if (!isset($comportamento_frequencias[$cod])) {
                $comportamento_frequencias[$cod] = [
                    'codigo' => $cod,
                    'descricao' => $desc,
                    'total' => 0
                ];
            }
            $comportamento_frequencias[$cod]['total'] += $total;
        }
        
        // Ordena por total desc
        usort($comportamento_frequencias, function($a, $b) { return $b['total'] <=> $a['total']; });
        
        // Gera lista conforme frequência
        $comportamento_atividades_ordenadas = [];
        foreach ($comportamento_frequencias as $item) {
            // Garantia extra: nunca incluir ECP03 na lista ordenada
            if (strpos($item['codigo'], 'ECP03') === 0) {
                continue;
            }
            
            $repeticoes = $item['total'];
            for ($i = 0; $i < $repeticoes; $i++) {
                $obj = new \stdClass();
                $obj->cod_ati_comportamento = $item['codigo'];
                $obj->desc_ati_comportamento = $item['descricao'];
                $comportamento_atividades_ordenadas[] = $obj;
                // Se for ECP03, só adiciona uma vez
                if (strpos($item['codigo'], 'ECP03') === 0) {
                    break;
                }
            }
        }

        // Socioemocional - IMPORTANTE: Excluir COMPLETAMENTE a atividade id=1 e código EIS01
        $socioemocional_frequencias = [];
        foreach ($socioemocional_agrupado as $item) {
            // Excluir EIS01 por id da atividade, código OU fk_id_pro_int_socio
            if (
                (isset($item->id_ati_int_soc) && $item->id_ati_int_soc == 1) ||
                (isset($item->cod_ati_int_soc) && $item->cod_ati_int_soc === 'EIS01') ||
                (isset($item->fk_id_pro_int_socio) && $item->fk_id_pro_int_socio == 1)
            ) {
                continue;
            }
            $cod = $item->cod_ati_int_soc;
            $desc = $item->desc_ati_int_soc;
            $total = $item->total;
            if (!isset($socioemocional_frequencias[$cod])) {
                $socioemocional_frequencias[$cod] = [
                    'codigo' => $cod,
                    'descricao' => $desc,
                    'total' => 0
                ];
            }
            $socioemocional_frequencias[$cod]['total'] += $total;
        }
        
        // Ordena por total desc
        usort($socioemocional_frequencias, function($a, $b) { return $b['total'] <=> $a['total']; });
        
        // Gera lista conforme frequência
        $socioemocional_atividades_ordenadas = [];
        foreach ($socioemocional_frequencias as $item) {
            // Exatamente como no comportamento: não precisa filtrar aqui, já está filtrado
            $repeticoes = $item['total'];
            for ($i = 0; $i < $repeticoes; $i++) {
                $obj = new \stdClass();
                $obj->cod_ati_int_soc = $item['codigo'];
                $obj->desc_ati_int_soc = $item['descricao'];
                $socioemocional_atividades_ordenadas[] = $obj;
            }
        }
        // --- FIM NOVO ---

        // Manter o cálculo original para os totais dos resumos
        $total_eixos = 0;
        
        // Soma as atividades do eixo Comunicação/Linguagem
        foreach ($comunicacao_linguagem_agrupado as $item) {
            // Pula a atividade EIS01 se por acaso estiver aqui
            if (isset($item->cod_ati_com_lin) && $item->cod_ati_com_lin === 'EIS01') {
                continue;
            }
            if (isset($item->total)) {
                $total_eixos += (int)$item->total;
            }
        }

        // Soma as atividades de todos os eixos para o total_atividades, excluindo EIS01
        $total_atividades = 0;
        foreach ($comunicacao_linguagem_agrupado as $item) {
            if (isset($item->cod_ati_com_lin) && $item->cod_ati_com_lin === 'EIS01') {
                continue;
            }
            if (isset($item->total)) {
                $total_atividades += (int)$item->total;
            }
        }
        foreach ($comportamento_agrupado as $item) {
            if (isset($item->cod_ati_comportamento) && $item->cod_ati_comportamento === 'ECP03') {
                continue;
            }
            if (isset($item->total)) {
                $total_atividades += (int)$item->total;
            }
        }
        foreach ($socioemocional_agrupado as $item) {
            if (
                (isset($item->cod_ati_int_soc) && $item->cod_ati_int_soc === 'EIS01') ||
                (isset($item->cod_ati_int_socio) && $item->cod_ati_int_socio === 'EIS01') ||
                (isset($item->fk_id_pro_int_socio) && $item->fk_id_pro_int_socio == 1)
            ) {
                continue;
            }
            if (isset($item->total)) {
                $total_atividades += (int)$item->total;
            }
        }
        
        // Soma as atividades do eixo Comportamento, EXCLUINDO a ECP03
        foreach ($comportamento_agrupado as $item) {
            // Pula a atividade ECP03
            if (isset($item->cod_ati_comportamento) && $item->cod_ati_comportamento === 'ECP03') {
                continue;
            }
            
            if (isset($item->total)) {
                $total_eixos += (int)$item->total;
            }
        }
        
        // Soma as atividades do eixo Interação Socioemocional, EXCLUINDO a EIS01
        foreach ($socioemocional_agrupado as $item) {
            // Pula qualquer EIS01 por código ou vínculo (total geral)
            if (
                (isset($item->cod_ati_int_soc) && $item->cod_ati_int_soc === 'EIS01') ||
                (isset($item->cod_ati_int_socio) && $item->cod_ati_int_socio === 'EIS01') ||
                (isset($item->fk_id_pro_int_socio) && $item->fk_id_pro_int_socio == 1)
            ) {
                continue;
            }
            if (isset($item->total)) {
                $total_eixos += (int)$item->total;
            }
        }
        
        // Definir os totais individuais para cada eixo
        $total_comunicacao_linguagem = 0;
        $total_comportamento = 0;
        $total_socioemocional = 0;
        
        // Calcular totais individuais
        foreach ($comunicacao_linguagem_agrupado as $item) {
            if (isset($item->total)) {
                $total_comunicacao_linguagem += (int)$item->total;
            }
        }
        
        foreach ($comportamento_agrupado as $item) {
            if (isset($item->cod_ati_comportamento) && $item->cod_ati_comportamento === 'ECP03') {
                continue;
            }
            if (isset($item->total)) {
                $total_comportamento += (int)$item->total;
            }
        }
        
        // Contar atividades de Interação Socioemocional (excluindo EIS01)
        foreach ($socioemocional_agrupado as $item) {
            // Pula qualquer EIS01 por código ou vínculo
            if (
                (isset($item->cod_ati_int_soc) && $item->cod_ati_int_soc === 'EIS01') ||
                (isset($item->cod_ati_int_socio) && $item->cod_ati_int_socio === 'EIS01') ||
                (isset($item->fk_id_pro_int_socio) && $item->fk_id_pro_int_socio == 1)
            ) {
                continue;
            }
            if (isset($item->total)) {
                $total_socioemocional += (int)$item->total;
            }
        }

        // --- NORMALIZAÇÃO GLOBAL DAS ATIVIDADES DOS 3 EIXOS ---
        // Junta todas as atividades dos três eixos em um único array
        $todas_atividades = [];
        foreach ($comunicacao_atividades_ordenadas as $item) {
            $todas_atividades[] = [
                'codigo' => $item->cod_ati_com_lin,
                'descricao' => $item->desc_ati_com_lin,
                'eixo' => 'comunicacao',
            ];
        }
        foreach ($comportamento_atividades_ordenadas as $item) {
            $todas_atividades[] = [
                'codigo' => $item->cod_ati_comportamento,
                'descricao' => $item->desc_ati_comportamento,
                'eixo' => 'comportamento',
            ];
        }
        foreach ($socioemocional_atividades_ordenadas as $item) {
            $todas_atividades[] = [
                'codigo' => $item->cod_ati_int_soc ?? $item->cod_ati_int_socio,
                'descricao' => $item->desc_ati_int_soc ?? $item->desc_ati_int_socio,
                'eixo' => 'socioemocional',
            ];
        }

$socioemocional_frequencias = [];
foreach ($socioemocional_agrupado as $item) {
    // Filtro para excluir a atividade EIS01 (id=1) de todas as contagens
    // Verifica se é a atividade com id=1 OU código EIS01
    if (
        (isset($item->id_ati_int_socio) && $item->id_ati_int_socio == 1) ||
        (isset($item->cod_ati_int_socio) && $item->cod_ati_int_socio === 'EIS01')
    ) {
        // Pula completamente esta atividade
        continue;
    }

    $cod = $item->cod_ati_int_socio;
    $desc = $item->desc_ati_int_socio;
    $total = $item->total;

    // Verifica novamente pelo código para garantir que não seja EIS01
    if (strpos($cod, 'EIS01') === 0) {
        continue;
    }

    if (!isset($socioemocional_frequencias[$cod])) {
        $socioemocional_frequencias[$cod] = [
            'codigo' => $cod,
            'descricao' => $desc,
            'total' => 0
        ];
    }
    $socioemocional_frequencias[$cod]['total'] += $total;
}

// Ordena por total desc
usort($socioemocional_frequencias, function($a, $b) { return $b['total'] <=> $a['total']; });

// Gera lista conforme frequência
$socioemocional_atividades_ordenadas = [];
foreach ($socioemocional_frequencias as $item) {
    // Garantia extra: nunca incluir EIS01 na lista ordenada
    if (strpos($item['codigo'], 'EIS01') === 0) {
        continue;
    }

    $repeticoes = $item['total'];
    for ($i = 0; $i < $repeticoes; $i++) {
        $obj = new \stdClass();
        $obj->cod_ati_int_socio = $item['codigo'];
        $obj->desc_ati_int_socio = $item['descricao'];
        $socioemocional_atividades_ordenadas[] = $obj;
    }
}
// --- FIM NOVO ---

// Manter o cálculo original para os totais dos resumos
$total_eixos = 0;

// Soma as atividades do eixo Comunicação/Linguagem
foreach ($comunicacao_linguagem_agrupado as $item) {
    // Pula a atividade EIS01 se por acaso estiver aqui
    if (isset($item->cod_ati_com_lin) && $item->cod_ati_com_lin === 'EIS01') {
        continue;
    }

    if (isset($item->total)) {
        $total_eixos += (int)$item->total;
    }
}

// Soma as atividades do eixo Comportamento, EXCLUINDO a ECP03
foreach ($comportamento_agrupado as $item) {
    // Pula a atividade ECP03
    if (isset($item->cod_ati_comportamento) && $item->cod_ati_comportamento === 'ECP03') {
        continue;
    }

    if (isset($item->total)) {
        $total_eixos += (int)$item->total;
    }
}

// Soma as atividades do eixo Interação Socioemocional, EXCLUINDO a EIS01
foreach ($socioemocional_agrupado as $item) {
    // Pula a atividade EIS01
    if (isset($item->cod_ati_int_socio) && $item->cod_ati_int_socio === 'EIS01') {
        continue;
    }

    if (isset($item->total)) {
        $total_eixos += (int)$item->total;
    }
}

// Definir os totais individuais para cada eixo
$total_comunicacao_linguagem = 0;
$total_comportamento = 0;
$total_socioemocional = 0;

// Calcular totais individuais
foreach ($comunicacao_linguagem_agrupado as $item) {
    if (isset($item->total)) {
        $total_comunicacao_linguagem += (int)$item->total;
    }
}

foreach ($comportamento_agrupado as $item) {
    if (isset($item->cod_ati_comportamento) && $item->cod_ati_comportamento === 'ECP03') {
        continue;
    }
    if (isset($item->total)) {
        $total_comportamento += (int)$item->total;
    }
}

// Contar atividades de Interação Socioemocional (excluindo EIS01)
// (Se necessário, coloque o foreach correto aqui, dentro do método monitoramentoAluno)
        // Calcula totais por eixo para os logs
        $total_comunicacao_linguagem = count($comunicacao_atividades_ordenadas);
        $total_comportamento = count($comportamento_atividades_ordenadas);
        $total_socioemocional = count($socioemocional_atividades_ordenadas);
        $total_eixos = $total_comunicacao_linguagem + $total_comportamento + $total_socioemocional;
        
        // --- NORMALIZAÇÃO GLOBAL DAS ATIVIDADES DOS 3 EIXOS ---
        // Junta todas as atividades dos três eixos em um único array
        $todas_atividades = [];
        foreach ($comunicacao_atividades_ordenadas as $item) {
            $todas_atividades[] = [
                'codigo' => $item->cod_ati_com_lin,
                'descricao' => $item->desc_ati_com_lin,
                'eixo' => 'comunicacao',
            ];
        }
        foreach ($comportamento_atividades_ordenadas as $item) {
            $todas_atividades[] = [
                'codigo' => $item->cod_ati_comportamento,
                'descricao' => $item->desc_ati_comportamento,
                'eixo' => 'comportamento',
            ];
        }
        foreach ($socioemocional_atividades_ordenadas as $item) {
            $todas_atividades[] = [
                'codigo' => $item->cod_ati_int_soc ?? $item->cod_ati_int_socio,
                'descricao' => $item->desc_ati_int_soc ?? $item->desc_ati_int_socio,
                'eixo' => 'socioemocional',
            ];
        }

        // Agrupa e conta quantas vezes cada atividade aparece
        $atividades_agrupadas = [];
        foreach ($todas_atividades as $atv) {
            $key = $atv['eixo'] . '|' . $atv['codigo'];
            if (!isset($atividades_agrupadas[$key])) {
                $atividades_agrupadas[$key] = [
                    'codigo' => $atv['codigo'],
                    'descricao' => $atv['descricao'],
                    'eixo' => $atv['eixo'],
                    'total' => 0
                ];
            }
            $atividades_agrupadas[$key]['total']++;
        }
        $atividades_agrupadas = array_values($atividades_agrupadas);

        // Soma total de atividades para o fator de normalização
        $total_atividades = array_sum(array_column($atividades_agrupadas, 'total'));
        // Fator arredondado igual à planilha: 1 casa decimal
        $fator = $total_atividades > 0 ? round($total_atividades / 40, 1) : 1;

        // --- SOLUÇÃO EXATAMENTE IGUAL À PLANILHA ---
        
        // Calcula o fator determinante (X) como na planilha
        // Na planilha: 99/40 = 2,475 que foi arredondado para 2,5
        $fator_determinante = round($total_atividades / 40, 1);
        Log::info('Fator determinante (X) = ' . $fator_determinante);
        
        // Aplica o fator determinante para cada atividade, exatamente como na planilha
        foreach ($atividades_agrupadas as $i => $atv) {
            // Divide a somatória pelo fator determinante
            $valor_normalizado = $atv['total'] / $fator_determinante;
            
            // Arredonda para o inteiro mais próximo com função de arredondamento específica
            // Para replicar exatamente o comportamento do Excel
            $atividades_agrupadas[$i]['aplicacoes'] = $this->arredondarExcel($valor_normalizado);
            $atividades_agrupadas[$i]['valor_exato'] = $valor_normalizado; // Para debug
        }
        
        // Verificar se a soma é exatamente 40
        $soma_final = 0;
        foreach ($atividades_agrupadas as $atv) {
            $soma_final += $atv['aplicacoes'];
        }
        
        // Log para debug
        Log::info('NORMALIZAÇÃO - SOMA APÓS ARREDONDAMENTO = ' . $soma_final . ' (deve ser 40)');
        
        // GARANTIA ABSOLUTA - Ajuste obrigatório para total exato de 40
        if ($soma_final != 40) {
            Log::warning('AJUSTE FINAL NECESSÁRIO: ' . (40 - $soma_final));
            $diff = 40 - $soma_final;
            
            // Primeira estratégia: ajustar pelos valores originais
            if ($diff > 0) {
                // Adicionar pontos restantes aos itens com maiores valores originais
                $indices_por_total = [];
                foreach ($atividades_agrupadas as $i => $atv) {
                    $indices_por_total[$i] = $atv['total'];
                }
                arsort($indices_por_total);
                
                $i = 0;
                foreach ($indices_por_total as $idx => $total) {
                    if ($i < $diff) {
                        $atividades_agrupadas[$idx]['aplicacoes'] += 1;
                        $i++;
                    } else {
                        break;
                    }
                }
            } else if ($diff < 0) {
                // Remover pontos excedentes dos itens com menores valores originais
                $indices_por_total = [];
                foreach ($atividades_agrupadas as $i => $atv) {
                    $indices_por_total[$i] = $atv['total'];
                }
                asort($indices_por_total); // Ordem crescente
                
                foreach ($indices_por_total as $idx => $total) {
                    if ($diff < 0 && $atividades_agrupadas[$idx]['aplicacoes'] > 0) {
                        $quanto_tirar = min(abs($diff), $atividades_agrupadas[$idx]['aplicacoes']);
                        $atividades_agrupadas[$idx]['aplicacoes'] -= $quanto_tirar;
                        $diff += $quanto_tirar;
                        if ($diff == 0) break;
                    }
                }
            }
        }
        
        // VERIFICAÇÃO FINAL RIGOROSA - Se ainda não está 40, força o ajuste
        // Verificamos novamente se chegamos exatamente a 40
        $soma_final = 0;
        foreach ($atividades_agrupadas as $atv) {
            $soma_final += $atv['aplicacoes'];
        }
        
        if ($soma_final != 40) {
            Log::error('FORÇANDO AJUSTE FINAL. Soma atual = ' . $soma_final);
            $diff = 40 - $soma_final;
            
            if ($diff > 0) {
                // Adicionar os pontos faltantes diretamente aos primeiros itens
                $i = 0;
                foreach ($atividades_agrupadas as $idx => $atv) {
                    if ($i < $diff) {
                        $atividades_agrupadas[$idx]['aplicacoes'] += 1;
                        $i++;
                    } else {
                        break;
                    }
                }
            } else if ($diff < 0) {
                // Retirar pontos diretamente dos últimos itens
                $i = count($atividades_agrupadas) - 1;
                while ($diff < 0 && $i >= 0) {
                    if ($atividades_agrupadas[$i]['aplicacoes'] > 0) {
                        $atividades_agrupadas[$i]['aplicacoes'] -= 1;
                        $diff++;
                    }
                    $i--;
                }
            }
        }
        
        // Verificação final - deve ser exatamente 40
        $soma_final = array_sum(array_column($atividades_agrupadas, 'aplicacoes'));
        Log::info('NORMALIZAÇÃO - SOMA FINAL VERIFICADA = ' . $soma_final . ' (DEVE SER EXATAMENTE 40)');
        
        // Log detalhado final
        $log_detalhes = [];
        $soma_verificacao = 0;
        foreach ($atividades_agrupadas as $atv) {
            $soma_verificacao += $atv['aplicacoes'];
            $log_detalhes[] = ["codigo" => $atv['codigo'], "total" => $atv['total'], "aplicacoes" => $atv['aplicacoes']];
        }
        Log::info('NORMALIZAÇÃO - SOMA FINAL VERIFICADA = ' . $soma_verificacao, $log_detalhes);
        // Agora a soma dos 3 eixos é exatamente 40, igual ao Excel/manual.

        // Separa novamente por eixo para exibir na view
        $atividades_normalizadas_comunicacao = [];
        $atividades_normalizadas_comportamento = [];
        $atividades_normalizadas_socioemocional = [];
        foreach ($atividades_agrupadas as $atv) {
            if ($atv['eixo'] === 'comunicacao') {
                $atividades_normalizadas_comunicacao[] = $atv;
            } elseif ($atv['eixo'] === 'comportamento') {
                $atividades_normalizadas_comportamento[] = $atv;
            } elseif ($atv['eixo'] === 'socioemocional') {
                $atividades_normalizadas_socioemocional[] = $atv;
            }
        }

        // --- DEBUG NORMALIZAÇÃO GLOBAL ---
        $debug_normalizacao = [
            'soma_normalizados_comunicacao' => array_sum(array_column($atividades_normalizadas_comunicacao, 'aplicacoes')),
            'soma_normalizados_comportamento' => array_sum(array_column($atividades_normalizadas_comportamento, 'aplicacoes')),
            'soma_normalizados_socioemocional' => array_sum(array_column($atividades_normalizadas_socioemocional, 'aplicacoes')),
        ];
        $debug_normalizacao['soma_normalizados_total'] = $debug_normalizacao['soma_normalizados_comunicacao'] + $debug_normalizacao['soma_normalizados_comportamento'] + $debug_normalizacao['soma_normalizados_socioemocional'];
        
        // Atualiza os totais por eixo para refletir os valores normalizados
        $total_comunicacao_linguagem = $debug_normalizacao['soma_normalizados_comunicacao'];
        $total_comportamento = $debug_normalizacao['soma_normalizados_comportamento'];
        $total_socioemocional = $debug_normalizacao['soma_normalizados_socioemocional'];
        $total_eixos = $debug_normalizacao['soma_normalizados_total']; // Deve ser exatamente 40

        $debug_info = json_encode($debug_normalizacao, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        Log::info('Debug Normalização GLOBAL:', $debug_normalizacao);

        // Retorna para a view com todas as variáveis necessárias
        return view('rotina_monitoramento.monitoramento_aluno', [
            'alunoDetalhado' => $aluno,
            'comunicacao_linguagem_agrupado' => $comunicacao_linguagem_agrupado,
            'comportamento_agrupado' => $comportamento_agrupado,
            'socioemocional_agrupado' => $socioemocional_agrupado,
            'comunicacao_atividades_ordenadas' => $comunicacao_atividades_ordenadas,
            'comportamento_atividades_ordenadas' => $comportamento_atividades_ordenadas,
            'socioemocional_atividades_ordenadas' => $socioemocional_atividades_ordenadas,
            'atividades_normalizadas_comunicacao' => $atividades_normalizadas_comunicacao,
            'atividades_normalizadas_comportamento' => $atividades_normalizadas_comportamento,
            'atividades_normalizadas_socioemocional' => $atividades_normalizadas_socioemocional,
            'total_eixos' => $total_eixos,
            'total_atividades' => $total_atividades,
            'total_comunicacao' => $total_comunicacao_linguagem,
            'total_comportamento' => $total_comportamento,
            'total_socioemocional' => $total_socioemocional,
            'comunicacao_resultados' => isset($comunicacao_resultados) ? $comunicacao_resultados : [],
            'comportamento_resultados' => isset($comportamento_resultados) ? $comportamento_resultados : [],
            'socioemocional_resultados' => isset($socioemocional_resultados) ? $socioemocional_resultados : [],
            'data_inicial_com_lin' => isset($data_inicial_com_lin) ? $data_inicial_com_lin : null,
            'detalhe' => isset($detalhe) ? $detalhe : null,
            'debug_info' => $debug_info,
            'debug_normalizacao' => $debug_normalizacao
        ]);
    } // FIM DO MÉTODO monitoramentoAluno

// Método para arredondar valores exatamente como o Excel faz
/**
 * Arredonda um valor usando a mesma lógica de arredondamento do Excel.
 * O Excel segue o padrão de arredondamento "Banker's Rounding"/"Round Half to Even":
 * - Se a parte decimal for menor que 0.5, arredonda para baixo
 * - Se a parte decimal for maior que 0.5, arredonda para cima
 * - Se a parte decimal for EXATAMENTE 0.5, arredonda para o valor par mais próximo
 * 
 * @param float $valor O valor a ser arredondado
 * @return int O valor arredondado como inteiro
 */
protected function arredondarExcel($valor) 
{
    // Para validar cada resultado, adicionamos log detalhado
    $parteInteira = floor($valor);
    $parteDecimal = $valor - $parteInteira;
    
    // Caso 1: parte decimal < 0.5 => arredonda para baixo
    if ($parteDecimal < 0.5) {
        return (int)$parteInteira;
    }
    // Caso 2: parte decimal > 0.5 => arredonda para cima
    else if ($parteDecimal > 0.5) {
        return (int)($parteInteira + 1);
    }
    // Caso 3: parte decimal = 0.5 => arredonda para o valor par mais próximo
    else {
        return (int)(($parteInteira % 2 == 0) ? $parteInteira : $parteInteira + 1);
    }
}

// Outros métodos do controller permanecem abaixo

public function processaEixoComLin(Request $request)
{
    $alunoId = $request->route('id');
    $eixo = EixoComunicacaoLinguagem::where('fk_alu_id_ecomling', $alunoId)->first();
    if (!$eixo) {
        return response()->json(['error' => 'Inventário não encontrado para o aluno'], 404);
    }
    $indices = [];
    for ($i = 1; $i <= 32; $i++) {
        $campo = 'ecm' . str_pad($i, 2, '0', STR_PAD_LEFT);
        if ($eixo->$campo == 1) {
            $indices[] = $i;
        }
    }
    $habilidades = [];
    foreach ($indices as $indice) {
        $hab = HabProComLin::where('fk_id_hab_com_lin', $indice)->first();
        if ($hab) {
            $habilidades[] = [
                'fk_hab_pro_com_lin' => $hab->id_hab_pro_com_lin,
                'fk_id_pro_com_lin' => $hab->fk_id_pro_com_lin,
                'fk_result_alu_id_ecomling' => $eixo->fk_alu_id_ecomling,
                'date_cadastro' => now(),
                'tipo_fase_com_lin' => $eixo->fase_inv_com_lin
            ];
        }
    }
    // Otimização: insert em lote com transação
    $resultadosInseridos = [];
    DB::transaction(function () use (&$habilidades, &$resultadosInseridos) {
        if (count($habilidades) > 0) {
            \App\Models\ResultEixoComLin::insert($habilidades);
            // Para exibir o JSON igual antes, buscamos os registros inseridos (opcional: pode-se retornar só os dados enviados)
            $resultadosInseridos = $habilidades;
        }
    });
    return response()->json(['message' => 'Resultados processados com sucesso', 'dados' => $resultadosInseridos]);
}

public function debugEixoComportamento(Request $request)
{
    $alunoId = $request->route('id');
    $eixo = EixoComportamento::where('fk_alu_id_ecomp', $alunoId)->first();
    // ... (restante do código)
    if (!$eixo) {
        return response()->json(['error' => 'Inventário não encontrado para o aluno'], 404);
    }
    $indices = [];
    for ($i = 1; $i <= 17; $i++) {
        $campo = 'ecp' . str_pad($i, 2, '0', STR_PAD_LEFT);
        if ($eixo->$campo == 1) {
            $indices[] = $i;
        }
    }
    $habilidades = [];
    foreach ($indices as $indice) {
        $hab = HabProComportamento::where('fk_id_hab_comportamento', $indice)->first();
        if ($hab) {
            $habilidades[] = [
                'fk_hab_pro_comportamento' => $hab->id_hab_pro_comportamento,
                'fk_id_pro_comportamento' => $hab->fk_id_pro_comportamento,
                'fk_result_alu_id_comportamento' => $eixo->fk_alu_id_ecomp,
                'date_cadastro' => now(),
                'tipo_fase_comportamento' => $eixo->fase_inv_comportamento
            ];
        }
    }
    // Otimização: insert em lote com transação
    $resultadosInseridos = [];
    DB::transaction(function () use (&$habilidades, &$resultadosInseridos) {
        if (count($habilidades) > 0) {
            \App\Models\ResultEixoComportamento::insert($habilidades);
            // Para exibir o JSON igual antes, buscamos os registros inseridos (opcional: pode-se retornar só os dados enviados)
            $resultadosInseridos = $habilidades;
        }
    });
    return response()->json(['message' => 'Resultados processados com sucesso', 'dados' => $resultadosInseridos]);
}

public function debugEixoIntSocio(Request $request)
{
    $alunoId = $request->route('id');
    $eixo = EixoInteracaoSocEmocional::where('fk_alu_id_eintsoc', $alunoId)
        ->orderByDesc('data_insert_int_socio')
        ->first();
    $indices = [];
    for ($i = 1; $i <= 18; $i++) {
        $campo = 'eis' . str_pad($i, 2, '0', STR_PAD_LEFT);
        if ($eixo && $eixo->$campo == 1) {
            $indices[] = $i;
        }
    }
    $habilidades = [];
    foreach ($indices as $indice) {
        $hab = HabProIntSoc::where('fk_id_hab_int_soc', $indice)->first();
        if ($hab) {
            $habilidades[] = [
                'fk_hab_pro_int_socio' => $hab->id_hab_pro_int_soc,
                'fk_id_pro_int_socio' => $hab->fk_id_pro_int_soc,
                'fk_result_alu_id_int_socio' => $eixo->fk_alu_id_eintsoc,
                'date_cadastro' => now(),
                'tipo_fase_int_socio' => $eixo->fase_inv_int_socio
            ];
        }
    }
    return [
        'indices_sim' => $indices,
        'habilidades_propostas' => $habilidades
    ];
}

public function inserirTodosEixos(Request $request, $alunoId = null, $fase_inventario = null)
{
    // Se o ID não foi passado como parâmetro, tenta obter da rota
    $alunoId = $alunoId ?? $request->route('id');
    
    if (!$alunoId) {
        \Log::error('ID do aluno não fornecido em inserirTodosEixos');
        return ['error' => 'ID do aluno não fornecido'];
    }

    $resultados = [];
    $alunoId = $request->route('id');
    $resultados = [];
    // Comunicação/Linguagem
    $eixoComunicacao = \App\Models\EixoComunicacaoLinguagem::where('fk_alu_id_ecomling', $alunoId)
        ->orderByDesc('data_insert_com_lin')
        ->first();
    $resultados['comunicacao_linguagem'] = [];
    if ($eixoComunicacao) {
        $indicesMarcados = [];
        for ($i = 1; $i <= 32; $i++) {
            $campo = 'ecm' . str_pad($i, 2, '0', STR_PAD_LEFT);
            if (isset($eixoComunicacao->$campo) && intval($eixoComunicacao->$campo) === 0) {
                $indicesMarcados[] = $i;
            }
        }
        if (count($indicesMarcados)) {
            $propostas = \App\Models\HabProComLin::whereIn('fk_id_hab_com_lin', $indicesMarcados)->get();
            $registros = [];
            foreach ($propostas as $proposta) {
                $registros[] = [
                    'fk_hab_pro_com_lin' => $proposta->fk_id_hab_com_lin,
                    'fk_id_pro_com_lin' => $proposta->fk_id_pro_com_lin,
                    'fk_result_alu_id_ecomling' => $alunoId,
                    'date_cadastro' => now(),
                    'tipo_fase_com_lin' => $eixoComunicacao->fase_inv_com_lin
                ];
            }
            if (count($registros)) {
                \App\Models\ResultEixoComLin::insert($registros);
                $resultados['comunicacao_linguagem'] = $registros;
            }
        }
    }
    // Comportamento
    $eixoComportamento = \App\Models\EixoComportamento::where('fk_alu_id_ecomp', $alunoId)
        ->orderByDesc('data_insert_comportamento')
        ->first();
    $resultados['comportamento'] = [];
    if ($eixoComportamento) {
        $indicesMarcados = [];
        for ($i = 1; $i <= 17; $i++) {
            $campo = 'ecp' . str_pad($i, 2, '0', STR_PAD_LEFT);
            if (isset($eixoComportamento->$campo) && intval($eixoComportamento->$campo) === 0) {
                $indicesMarcados[] = $i;
            }
        }
        if (count($indicesMarcados)) {
            $propostas = \App\Models\HabProComportamento::whereIn('fk_id_hab_comportamento', $indicesMarcados)->get();
            $registros = [];
            foreach ($propostas as $proposta) {
                $registros[] = [
                    'fk_hab_pro_comportamento' => $proposta->fk_id_hab_comportamento,
                    'fk_id_pro_comportamento' => $proposta->fk_id_pro_comportamento,
                    'fk_result_alu_id_comportamento' => $alunoId,
                    'date_cadastro' => now(),
                    'tipo_fase_comportamento' => $eixoComportamento->fase_inv_comportamento
                ];
            }
            if (count($registros)) {
                \App\Models\ResultEixoComportamento::insert($registros);
                $resultados['comportamento'] = $registros;
            }
        }
    }
    // Interação Socioemocional
    $eixoIntSocio = \App\Models\EixoInteracaoSocEmocional::where('fk_alu_id_eintsoc', $alunoId)
        ->orderByDesc('data_insert_int_socio')
        ->first();
    $resultados['interacao_socioemocional'] = [];
    if ($eixoIntSocio) {
        $indicesMarcados = [];
        for ($i = 1; $i <= 18; $i++) {
            $campo = 'eis' . str_pad($i, 2, '0', STR_PAD_LEFT);
            if (isset($eixoIntSocio->$campo) && intval($eixoIntSocio->$campo) === 0) {
                $indicesMarcados[] = $i;
            }
        }
        if (count($indicesMarcados)) {
            $propostas = \App\Models\HabProIntSoc::whereIn('fk_id_hab_int_soc', $indicesMarcados)->get();
            $registros = [];
            foreach ($propostas as $proposta) {
                $registros[] = [
                    'fk_hab_pro_int_socio' => $proposta->fk_id_hab_int_soc,
                    'fk_id_pro_int_socio' => $proposta->fk_id_pro_int_soc,
                    'fk_result_alu_id_int_socio' => $alunoId,
                    'tipo_fase_int_socio' => $fase_inventario,
                ];
            }
            if (count($registros)) {
                \App\Models\ResultEixoIntSocio::insert($registros);
                $resultados['interacao_socioemocional'] = $registros;
            }
        }
    }
    return $resultados;
}
}
