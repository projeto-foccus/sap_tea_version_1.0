<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use Barryvdh\DomPDF\Facade\PDF;
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
            
            // Cria o documento Word
            $phpWord = new PhpWord();
            $section = $phpWord->addSection();
            
            // Adiciona o título
            $section->addTitle('Perfil do Estudante - ' . $aluno->alu_nome, 1);
            
            // Adiciona os dados por seção
            foreach ($dadosOrganizados as $secao => $campos) {
                $section->addTitle($secao, 2);
                foreach ($campos as $campo => $valor) {
                    $section->addText("{$campo}: {$valor}");
                }
            }
            
            // Garante que o diretório existe
            $publicPath = public_path('downloads');
                'aluno' => $aluno,
                'dados' => $dadosOrganizados
            ]);
            
            // Garante que o diretório existe
            $publicPath = public_path('downloads');
            if (!file_exists($publicPath)) {
                mkdir($publicPath, 0777, true);
            }
            
            // Salva o Excel
            $excelPath = $publicPath . "/perfil_{$aluno->alu_nome}.xlsx";
            $excel->store('xlsx', $excelPath);
            
            // Salva o PDF
            $pdfPath = $publicPath . "/perfil_{$aluno->alu_nome}.pdf";
            $pdf->save($pdfPath);
            
            // Retorna os arquivos para download
            return response()->download($excelPath, "perfil_{$aluno->alu_nome}.xlsx");
            
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
            
            // Cria o Excel
            return Excel::create('perfil_' . $aluno->alu_nome, function($excel) use ($dadosOrganizados) {
                $excel->sheet('Dados', function($sheet) use ($dadosOrganizados) {
                    // Adiciona o cabeçalho
                    $sheet->row(1, ['Seção', 'Campo', 'Valor']);
                    
                    $row = 2;
                    foreach ($dadosOrganizados as $secao => $campos) {
                        foreach ($campos as $campo => $valor) {
                            $sheet->row($row, [$secao, $campo, $valor]);
                            $row++;
                        }
                    }
                });
            })->download('xlsx');
            
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
            
            // Cria o PDF
            $pdf = PDF::loadView('alunos.pdf_template', [
                'aluno' => $aluno,
                'dados' => $dadosOrganizados
            ]);
            
            return $pdf->download('perfil_' . $aluno->alu_nome . '.pdf');
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao gerar PDF',
                'message' => $e->getMessage()
            ], 500);
        }
    }
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao gerar documentos',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
