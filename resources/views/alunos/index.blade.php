@extends('layouts.app')

@section('styles')
<style>
    .card-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
    }
    .student-card {
        width: 250px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: transform 0.3s ease;
    }
    .student-card:hover {
        transform: translateY(-5px);
    }
    .student-header {
        padding: 15px;
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
    }
    .student-body {
        padding: 15px;
    }
    .student-name {
        font-size: 18px;
        font-weight: bold;
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .btn-perfil {
        display: block;
        width: 100%;
        padding: 10px;
        text-align: center;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }
    .btn-perfil-incompleto {
        background-color: #f8d7da;
        color: #721c24;
    }
    .btn-perfil-completo {
        background-color: #d4edda;
        color: #155724;
    }
    .page-title {
        text-align: center;
        margin-bottom: 30px;
        color: #343a40;
    }
    .no-students {
        text-align: center;
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 8px;
        margin: 20px auto;
        max-width: 500px;
    }
</style>
@endsection

@section('content')
<main class="container py-4">
    <h1 class="page-title">Lista de Estudantes</h1>
    
    @if(isset($alunos) && count($alunos) > 0)
        <div class="card-container">
            @foreach($alunos as $aluno)
                <div class="student-card">
                    <div class="student-header">
                        <h3 class="student-name">{{ $aluno->alu_nome }}</h3>
                    </div>
                    <div class="student-body">
                        <a href="{{ route('sondagem.atualizaperfil', $aluno->alu_id) }}" 
                           class="btn-perfil {{ $aluno->flag_perfil == 'S' ? 'btn-perfil-completo' : 'btn-perfil-incompleto' }}">
                            {{ $aluno->flag_perfil == 'S' ? 'Perfil Completo' : 'Completar Perfil' }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="no-students">
            <p>Nenhum estudante encontrado.</p>
        </div>
    @endif
</main>
@endsection

