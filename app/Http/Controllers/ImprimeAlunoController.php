<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Aluno;
use Illuminate\Support\Facades\Auth;
class ImprimeAlunoController extends Controller
{
    public function imprimeAluno(Request $request)
    {
        $nome = $request->input('nome', '');
        
        // Obter o professor logado
        $professor_logado = auth('funcionario')->user();
        $professor_id = $professor_logado ? $professor_logado->func_id : null;
        
        // Buscar alunos relacionados ao professor logado com eager loading
        $alunos = Aluno::with([
            'matriculas.modalidade',
            'matriculas.turma.enturmacao'
        ])
        ->where('alu_nome', 'like', "%{$nome}%")
        ->whereHas('matriculas.turma', function($query) use ($professor_id) {
            $query->where('fk_cod_func', $professor_id);
        })
        ->orderBy('alu_nome', 'asc')
        ->paginate(10);

        return view('alunos.perfil_estudante_aluno', compact('alunos'));
    }
}
