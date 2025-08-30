<?php

namespace App\Services;

use App\Models\ControleFasesSondagem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ControleFasesService
{
    /**
     * Atualiza a fase da sondagem para um determinado aluno e ano.
     *
     * @param int $alunoId
     * @param string $fase O nome da fase (ex: 'inicial', 'continuada1', 'continuada2', 'final').
     * @return ControleFasesSondagem O registro atualizado ou criado.
     */
    public function atualizarFase(int $alunoId, string $fase)
    {
        $anoAtual = Carbon::now()->year;

        // Mapeia a fase de entrada para as colunas do banco de dados
        $mapeamentoFases = [
            'inicial'     => ['fase' => 'fase_inicial', 'flag' => 'flag_inicial', 'valor' => 'In'],
            'continuada1' => ['fase' => 'fase_cont1',   'flag' => 'flag_c1',      'valor' => 'Cont1'],
            'continuada2' => ['fase' => 'fase_cont2',   'flag' => 'flag_c2',      'valor' => 'Cont2'],
            'final'       => ['fase' => 'fase_final',   'flag' => 'flag_final',   'valor' => 'Final'],
        ];

        // Caso a fase de entrada seja 'In', trata como 'inicial'
        if ($fase === 'In') {
            $fase = 'inicial';
        }

        if (!isset($mapeamentoFases[$fase])) {
            Log::warning('Tentativa de atualizar fase desconhecida.', ['aluno_id' => $alunoId, 'fase' => $fase]);
            return null; // Ou lançar uma exceção
        }

        $colunas = $mapeamentoFases[$fase];

        // Encontra o registro pelo aluno e ano, ou cria um novo se não existir.
        // Atualiza a flag da fase correspondente para '*'.
        $registro = ControleFasesSondagem::updateOrCreate(
            [
                'id_aluno' => $alunoId,
                'ano'      => $anoAtual,
            ],
            [
                $colunas['fase'] => $colunas['valor'],
                $colunas['flag'] => '*',
            ]
        );

        Log::info('Fase de sondagem atualizada com sucesso.', ['registro_id' => $registro->id]);

        return $registro;
    }
}
