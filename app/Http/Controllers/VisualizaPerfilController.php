<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aluno;
use App\Models\PerfilEstudante;
use App\Models\PersonalidadeAluno;
use App\Models\Comunicacao;
use App\Models\Preferencia;
use App\Models\PerfilFamilia;
use App\Models\PerfilProfissional;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VisualizaPerfilController extends Controller
{
    /**
     * Exibe o perfil do estudante em modo de visualização.
     */
    public function visualizaPerfil($id)
    {
        try {
            // Busca o aluno
            $aluno = Aluno::findOrFail($id);
            
            // Busca o perfil do estudante e modelos relacionados
            $perfil = PerfilEstudante::where('fk_id_aluno', $id)->first();
            $personalidade = PersonalidadeAluno::where('fk_id_aluno', $id)->first();
            $comunicacao = Comunicacao::where('fk_id_aluno', $id)->first();
            $preferencia = Preferencia::where('fk_id_aluno', $id)->first();
            $perfilFamilia = PerfilFamilia::where('fk_id_aluno', $id)->first();
            // Busca todos os profissionais do aluno
            $profissionais = PerfilProfissional::where('fk_id_aluno', $id)->get();
            $perfilProfissional = $profissionais->first();
            
            // Se não existir perfil, cria objetos vazios para evitar erros na view
            if (!$perfil) $perfil = new PerfilEstudante();
            if (!$personalidade) $personalidade = new PersonalidadeAluno();
            if (!$comunicacao) $comunicacao = new Comunicacao();
            if (!$preferencia) $preferencia = new Preferencia();
            if (!$perfilFamilia) $perfilFamilia = new PerfilFamilia();
            if (!$perfilProfissional) $perfilProfissional = new PerfilProfissional();
            
            // Retorna a view com os dados
            return view('alunos.visualiza_perfil_estudante', [
                'aluno' => $aluno,
                'perfil' => $perfil,
                'personalidade' => $personalidade,
                'comunicacao' => $comunicacao,
                'preferencia' => $preferencia,
                'perfilFamilia' => $perfilFamilia,
                'perfilProfissional' => $perfilProfissional,
                'profissionais' => $profissionais,
                'modo' => 'visualizar'
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao visualizar perfil do estudante: ' . $e->getMessage());
            return redirect()->route('alunos.index')->with('error', 'Erro ao visualizar perfil do estudante.');
        }
    }
    
    /**
     * Exibe o perfil do estudante em modo de edição.
     */
    public function editaPerfil($id)
    {
        try {
            // Busca o aluno
            $aluno = Aluno::findOrFail($id);
            
            // Busca o perfil do estudante e modelos relacionados
            $perfil = PerfilEstudante::where('fk_id_aluno', $id)->first();
            $personalidade = PersonalidadeAluno::where('fk_id_aluno', $id)->first();
            $comunicacao = Comunicacao::where('fk_id_aluno', $id)->first();
            $preferencia = Preferencia::where('fk_id_aluno', $id)->first();
            $perfilFamilia = PerfilFamilia::where('fk_id_aluno', $id)->first();
            // Busca todos os profissionais do aluno
            $profissionais = PerfilProfissional::where('fk_id_aluno', $id)->get();
            $perfilProfissional = $profissionais->first();
            
            // Se não existir perfil, cria objetos vazios para evitar erros na view
            if (!$perfil) $perfil = new PerfilEstudante();
            if (!$personalidade) $personalidade = new PersonalidadeAluno();
            if (!$comunicacao) $comunicacao = new Comunicacao();
            if (!$preferencia) $preferencia = new Preferencia();
            if (!$perfilFamilia) $perfilFamilia = new PerfilFamilia();
            if (!$perfilProfissional) $perfilProfissional = new PerfilProfissional();
            
            // Retorna a view com os dados
            return view('alunos.visualiza_perfil_estudante', [
                'aluno' => $aluno,
                'perfil' => $perfil,
                'personalidade' => $personalidade,
                'comunicacao' => $comunicacao,
                'preferencia' => $preferencia,
                'perfilFamilia' => $perfilFamilia,
                'perfilProfissional' => $perfilProfissional,
                'profissionais' => $profissionais,
                'modo' => 'editar'
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao editar perfil do estudante: ' . $e->getMessage());
            return redirect()->route('alunos.index')->with('error', 'Erro ao editar perfil do estudante.');
        }
    }
}
