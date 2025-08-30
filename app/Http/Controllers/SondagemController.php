<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SondagemController extends Controller
{
    public function resultadoAluno($alu_id)
    {
        // Busca pelo campo correto na tabela eixo_com_lin
        $eixo = \DB::table('eixo_com_lin')->where('fk_alu_id_ecomling', $alu_id)->first();

        if (!$eixo) {
            return response()->json(['error' => 'Aluno não encontrado'], 404);
        }

        // Monta array com os campos ecm01...ecm32 que são igual a 1
        $valores = [];
        for ($i = 1; $i <= 32; $i++) {
            $campo = 'ecm' . str_pad($i, 2, '0', STR_PAD_LEFT);
            if ($eixo->$campo == 1) {
                // Aqui, assumimos que o valor para consulta é o número do campo
                $valores[] = $i;
            }
        }

        // INNER JOIN lógico entre os valores marcados e hab_pro_com_lin pelo campo fk_id_hab_com_lin
        $agrupamento = \DB::table('hab_pro_com_lin')
            ->select('hab_pro_com_lin.fk_id_pro_com_lin', \DB::raw('COUNT(*) as total'))
            ->whereIn('hab_pro_com_lin.fk_id_hab_com_lin', $valores)
            ->groupBy('hab_pro_com_lin.fk_id_pro_com_lin')
            ->get();

        // Conta quantos campos ecmXX são iguais a 0
        $total_zeros = 0;
        $lista_zeros = [];
        for ($i = 1; $i <= 32; $i++) {
            $campo = 'ecm' . str_pad($i, 2, '0', STR_PAD_LEFT);
            if ($eixo->$campo == 0) {
                $total_zeros++;
                $lista_zeros[] = $campo;
            }
        }

        // Monta o retorno
        $retorno = [
            'fk_alu_id_ecomling' => $eixo->fk_alu_id_ecomling,
            'agrupamento' => $agrupamento,
            'total_zeros' => $total_zeros,
            'lista_zeros' => $lista_zeros
        ];

        // Se for requisição JSON, retorna JSON
        if (request()->wantsJson()) {
            return response()->json($retorno);
        }
        // Senão, retorna a view HTML
        return view('sondagem.resultado_agrupamento', [
            'agrupamento' => $agrupamento,
            'total_zeros' => $total_zeros,
            'lista_zeros' => $lista_zeros
        ]);
    }
}
