<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Aluno;

class DocumentController extends Controller
{
    public function generateWordExcel(Request $request)
    {
        try {
            // Busca o aluno
            $aluno_id = $request->input('aluno_id');
            $aluno = Aluno::find($aluno_id);
            
            if (!$aluno) {
                return response()->json(['error' => 'Aluno não encontrado'], 404);
            }
            
            // Coleta os dados do formulário
            $dados = $request->except(['_token', 'aluno_id']);
            
            // Organiza os dados por seções
            $dadosOrganizados = [
                'Dados Pessoais' => [],
                'Perfil do Estudante' => [],
                'Personalidade' => [],
                'Preferências' => [],
                'Informações da Família' => [],
                'Profissionais' => []
            ];
            
            foreach ($dados as $key => $value) {
                if (strpos($key, 'profissional_') !== false) {
                    $dadosOrganizados['Profissionais'][$key] = $value;
                } elseif (strpos($key, 'familia_') !== false) {
                    $dadosOrganizados['Informações da Família'][$key] = $value;
                } elseif (strpos($key, 'preferencia_') !== false) {
                    $dadosOrganizados['Preferências'][$key] = $value;
                } elseif (strpos($key, 'personalidade_') !== false) {
                    $dadosOrganizados['Personalidade'][$key] = $value;
                } elseif (strpos($key, 'perfil_') !== false) {
                    $dadosOrganizados['Perfil do Estudante'][$key] = $value;
                } else {
                    $dadosOrganizados['Dados Pessoais'][$key] = $value;
                }
            }
            
            // Lógica para gerar o documento Word ou Excel
            // ...
            
            return response()->json([
                'success' => true,
                'message' => 'Documento gerado com sucesso!',
                'data' => $dadosOrganizados
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao gerar documentos',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function downloadExcel(Request $request)
    {
        try {
            // Lógica para gerar o Excel
            // ...
            
            return response()->json([
                'success' => true,
                'message' => 'Excel gerado com sucesso!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao gerar Excel',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function downloadPDF(Request $request)
    {
        try {
            // Lógica para gerar o PDF
            // ...
            
            return response()->json([
                'success' => true,
                'message' => 'PDF gerado com sucesso!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao gerar PDF',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
