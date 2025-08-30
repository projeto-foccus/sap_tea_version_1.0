<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aluno;
use Mpdf\Mpdf;

class GeneratePDFController extends Controller
{
    public function generatePDF(Request $request)
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
            
            // Configura o MPDF
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 20,
                'margin_bottom' => 20,
                'margin_header' => 10,
                'margin_footer' => 10,
                'orientation' => 'P',
                'default_font_size' => 12,
                'default_font' => 'arial'
            ]);

            // Carrega o template
            $html = view('alunos.pdf_template', [
                'aluno' => $aluno,
                'dados' => $dadosOrganizados
            ])->render();

            // Gera o PDF
            $mpdf->WriteHTML($html);
            $pdfContent = $mpdf->Output('', 'S');

            // Retorna o PDF para download
            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="perfil_estudante_' . $aluno->alu_nome . '.pdf"');
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao gerar PDF',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
