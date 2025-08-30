@extends('index')

@section('content')
    <h2>Exemplo de Listagem de Atividades Monitoradas</h2>
    {{-- Debug: Mostra o array de dados recebido --}}
    <pre>{{ print_r($dados, true) }}</pre>

    <table border="1" cellpadding="8" style="margin-bottom:40px;">
        <thead>
            <tr>
                <th>Eixo</th>
                <th>Código da Atividade</th>
                <th>Data Aplicação</th>
                <th>Realizado?</th>
                <th>Observações</th>
                <th>Registro Timestamp</th>
            </tr>
        </thead>
        <tbody>
            @foreach(['comunicacao', 'comportamento', 'socioemocional'] as $eixo)
                @if(isset($dados[$eixo]))
                    @foreach($dados[$eixo] as $cod_atividade => $registros)
                        @foreach($registros as $registro)
                            <tr>
                                <td>{{ ucfirst($eixo) }}</td>
                                <td>{{ $cod_atividade }}</td>
                                <td>{{ $registro['data_aplicacao'] }}</td>
                                <td>
                                    <input type="checkbox" disabled @if($registro['realizado']) checked @endif>
                                </td>
                                <td>{{ $registro['observacoes'] }}</td>
                                <td>{{ $registro['registro_timestamp'] }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                @endif
            @endforeach
        </tbody>
    </table>

    <h3>Cadastro de Nova Atividade</h3>
    <form method="POST" action="{{ route('monitoramento.salvar') }}">
        @csrf
        <input type="hidden" name="aluno_id" value="{{ $alunoId ?? '' }}">
        <label>Eixo:</label>
        <select name="eixo">
            <option value="comunicacao">Comunicação</option>
            <option value="comportamento">Comportamento</option>
            <option value="socioemocional">Socioemocional</option>
        </select>
        <br>
        <label>Código da Atividade:</label>
        <input type="text" name="cod_atividade">
        <br>
        <label>Data Aplicação:</label>
        <input type="date" name="data_aplicacao">
        <br>
        <label>Realizado?</label>
        <input type="checkbox" name="realizado" value="1">
        <br>
        <label>Observações:</label>
        <input type="text" name="observacoes">
        <br>
    </form>
@endsection
