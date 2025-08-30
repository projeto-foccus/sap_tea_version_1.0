@extends('index')

@section('content')
<div class="container mt-4">
    <h2>Relação dos estudantes</h2>

    @if(isset($professor_nome))
    <div class="alert alert-info" style="font-size:1.2em;">
        <strong>Professor Responsável:</strong> {{ $professor_nome }}
    </div>
@endif


    <!-- Formulário de Pesquisa -->
    <form id = "pesquisaForm" method="POST" action="{{ route('inserir_perfil') }}">
        <div class="input-group mb-3">
            <input type="text" name="nome" class="form-control" placeholder="Pesquisar por estudante"
                   value="{{ request('nome') }}">
            <button id="pesquisarBtn" class="btn btn-primary" type="submit">Pesquisar</button>

        </div>
    </form>

<script>
document.getElementById('pesquisarBtn').addEventListener('click', function(event) {
    event.preventDefault(); // Previne o envio padrão do formulário

    // Pega o valor do campo de pesquisa
    var nome = document.querySelector('input[name="nome"]').value;

    // Redireciona para a rota imprime_aluno com o parâmetro nome
    window.location.href = "{{route('imprime_aluno') }}?nome=" + nome;
});



</script>

    
    <!-- Tabela de Resultados -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>RA do estudante</th>
                <th>Nome do estudante</th>
                <th>Série</th>
                <th>Seguimento</th>
                <th>Responsavel</th>
                <th>Tel. Responsavel</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($alunos as $aluno)
                <tr>
                    <!-- Número da linha -->
                    <td>{{ $loop->iteration }}</td> 
                    <!-- Dados do aluno -->
                    <td>{{ $aluno->alu_ra }}</td>
                    <td>{{ $aluno->alu_nome }}</td>
                    <td>@if($aluno->matriculas->isNotEmpty() && $aluno->matriculas->first()->turma) {{ $aluno->matriculas->first()->turma->enturmacao->desc_serie ?? '-' }} @else - @endif</td>
                    <td>@if($aluno->matriculas->isNotEmpty() && $aluno->matriculas->first()->modalidade) {{ $aluno->matriculas->first()->modalidade->desc_modalidade ?? '-' }} @else - @endif</td>
                    <td>{{ $aluno->alu_nome_resp ?? '-' }}</td>
                    <td>{{ $aluno->alu_tel_resp ?? '-' }}</td>

                    <!-- Botão cadastra perfil -->
                    <td>
                        @if($aluno->flag_perfil === "*")
                            <button class="btn btn-success btn-sm text-white" style="background-color: #4caf50; border-color: #4caf50; cursor: not-allowed; width: 150px; height: 38px; display: inline-block;" disabled>Perfil Cadastrado</button>
                        @else
                            <a href="{{ route('alunos.index', ['id' => $aluno->alu_id]) }}" 
                               class="btn btn-primary btn-sm" style="width: 150px; height: 38px; display: inline-block;">Cadastra Perfil</a>
                        @endif
                    </td>

                    <!-- Botão visualiza/atualiza -->
                    <td>
                        @if($aluno->flag_perfil === null)
                            <button class="btn btn-warning btn-sm text-white" 
                                    style="background-color: #e67e22; border-color: #d35400; width: 150px; height: 38px; display: inline-block;"
                                    disabled>Visualiza Perfil</button>
                        @else
                            <a href="{{ route('visualizar.perfil', ['id' => $aluno->alu_id]) }}" 
                               class="btn btn-primary btn-sm" style="width: 150px; height: 38px; display: inline-block;">Visualiza Perfil</a>
                        @endif
                    </td>
                </tr>
            @empty
                <!-- Caso não existam alunos -->
                <tr>
                    <td colspan="8" class="text-center">Nenhum estudante encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Paginação -->
    @if ($alunos instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="d-flex justify-content-center">
            {{ $alunos->links() }}
        </div>
    @endif

</div>
@endsection
