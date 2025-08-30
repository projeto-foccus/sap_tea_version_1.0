@extends('index')

@section('content')
<div class="container mt-4">
    <h2>{{ $titulo ?? 'Alunos matriculados' }}</h2>

    @if(isset($professor_nome))
        <div class="alert alert-secondary mb-3">
            <strong>Professor Responsável:</strong> {{ $professor_nome }}
        </div>
    @endif

    <!-- Formulário de Pesquisa -->
    <form id="pesquisaForm" method="GET" action="{{ route('perfil.estudante.independente') }}">
        <div class="input-group mb-3">
            <input type="text" name="nome" class="form-control" placeholder="Pesquisar por estudante"
                   value="{{ request('nome') }}">
            <button class="btn btn-primary" type="submit">Pesquisar</button>
        </div>
    </form>

    <!-- Tabela de Resultados -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>RA do estudante</th>
                <th>Nome do estudante</th>
                <th>Nome da Escola</th>
                <th>Segmento</th>
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
                    <td>{{ optional(optional($aluno->matriculas->first())->turma)->escola->esc_razao_social ?? '---' }}</td>
                    <td>{{ optional(optional(optional($aluno->matriculas->first())->modalidade)->tipo)->desc_modalidade ?? '---' }}</td>
                  
                    <!-- Botões de ação flexíveis -->
                    <td>
                        @if(isset($botoes))
                            @foreach($botoes as $botao)
                                @if(!empty($aluno->alu_id))
                                    @php
                                        $classe = is_callable($botao['classe']) ? $botao['classe']($aluno) : $botao['classe'];
                                    @endphp
                                    <a href="{{ route($botao['rota'], ['id' => $aluno->alu_id]) }}" class="btn {{ $classe }} btn-sm">{{ $botao['label'] }}</a>
                                @endif
                            @endforeach
                        @endif

                        @if(($exibeBotaoInventario ?? false) && $aluno->flag_inventario !== "*")
                            <a href="{{ route($rota_acao ?? 'alunos.inventario', ['id' => $aluno->alu_id]) }}" class="btn btn-primary btn-sm d-inline-block align-middle">Sondagem Inicial</a>
                        @elseif(($exibeBotaoInventario ?? false) && $aluno->flag_inventario === "*")
                            <button class="btn btn-danger btn-sm d-inline-block align-middle" style="background-color:#e74c3c; border-color:#c0392b; color:#fff; opacity:0.8;" disabled>Sondagem Inicial</button>
                        @endif

                        @if($exibeBotaoPdf ?? false)
                            @if($aluno->flag_inventario === null)
                                <button class="btn btn-warning btn-sm text-white" style="background-color: #e67e22; border-color: #d35400;" disabled>Visualiza - gera Pdf </button>
                            @else
                                <a href="{{ route($rota_pdf ?? 'visualizar.inventario', ['id' => $aluno->alu_id]) }}" class="btn btn-primary btn-sm">Visualiza - gera Pdf </a>
                            @endif
                        @endif
                    </td>
                </tr>
            @empty
                <!-- Caso não existam estudantes -->
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
