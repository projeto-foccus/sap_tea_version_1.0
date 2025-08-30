<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InserirEixoController extends Controller
{
    public function store(Request $request, $id)
    {
        // Log para depuração: ver o que chega do formulário
        \Log::info('Chegou no método store do InserirEixoController', $request->all());
        // Exemplo de validação (ajuste conforme seus campos reais)
        $validated = $request->validate([
            'aluno_id' => 'required|integer',
            'responsavel' => 'required',
            'suporte' => 'required',
            'comunicacao' => 'required',
            // Não exigir campos dinâmicos como ecp17, ecp01, etc.
            // Outros campos podem ser validados manualmente se necessário
        ]);

        try {
            // Exemplo de inserção (ajuste para os campos reais do seu modelo)
            $inventario = new \App\Models\PreenchimentoInventario();
            $inventario->aluno_id = $request->input('aluno_id');
            $inventario->responsavel = $request->input('responsavel');
            $inventario->suporte = $request->input('suporte');
            $inventario->comunicacao = $request->input('comunicacao');
            // Adicione outros campos aqui
            $inventario->save();

            return redirect()->back()->with('success', 'Inventário inserido com sucesso!');
        } catch (\Exception $e) {
            \Log::error('Erro ao inserir inventário: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao inserir inventário.');
        }
    }
}
