<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aluno;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportExcelController extends Controller
{
    public function export(Request $request)
    {
        try {
            $aluno_id = $request->input('aluno_id');
            $aluno = Aluno::find($aluno_id);
            
            if (!$aluno) {
                return response()->json(['error' => 'Aluno não encontrado'], 404);
            }
            
            // Coleta todos os dados do formulário
            $dados = $request->all();
            
            // Remove campos que não queremos no Excel
            unset($dados['_token']);
            unset($dados['aluno_id']);

            // Organiza os dados por seções
            $dadosOrganizados = [
                'Dados Pessoais' => [],
                'Perfil do Estudante' => [],
                'Personalidade' => [],
                'Preferências' => [],
                'Informações da Família' => [],
                'Profissionais' => []
            ];

            // Distribui os dados nas seções
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

            // Cria um array para o Excel
            $excelData = [];
            foreach ($dadosOrganizados as $section => $fields) {
                $excelData[] = ['Seção: ' . $section];
                foreach ($fields as $key => $value) {
                    $excelData[] = [
                        str_replace('_', ' ', ucwords($key)) => $value
                    ];
                }
                $excelData[] = ['']; // Linha em branco entre seções
            }

            // Retorna o Excel
            return Excel::download(new \Illuminate\Support\Collection($excelData), 'perfil_estudante_' . $aluno->alu_nome . '.xlsx');
            
        } catch (\Exception $e) {
            \Log::error('Erro ao exportar Excel: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao exportar Excel: ' . $e->getMessage()], 500);
        }
    }
}
