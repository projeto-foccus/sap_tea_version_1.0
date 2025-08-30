@extends('index')

@section('content')
<style>
    .monitoramento-container {
        max-width: 900px;
        margin: 30px auto;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 8px #0001;
        padding: 32px 24px;
        font-family: Arial, sans-serif;
    }
    .monitoramento-container h2 {
        text-align: center;
        color: #333;
        margin-bottom: 28px;
    }
    .dados-aluno, .atividades-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 18px;
    }
    .dados-aluno th, .dados-aluno td, .atividades-table th, .atividades-table td {
        border: 1px solid #e0e0e0;
        padding: 10px;
        text-align: center;
        font-size: 15px;
    }
    .dados-aluno th {
        background: #f5f5f5;
        color: #555;
    }
    .dados-aluno td {
        background: #fafbfc;
    }
    .atividades-table th {
        background: #f0f7fa;
        color: #226;
    }
    .atividades-table td {
        background: #fff;
    }
    .form-group {
        margin-bottom: 14px;
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }
    .form-group label {
        font-weight: bold;
        width: 160px;
        display: inline-block;
    }
    .form-group input, .form-group select {
        flex: 1;
        min-width: 180px;
        padding: 7px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }
    .btn-group {
        display: flex;
        justify-content: center;
        gap: 18px;
        margin-top: 24px;
    }
    .btn-m {
        padding: 10px 24px;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        font-weight: bold;
        color: #fff;
        background: #007bff;
        transition: background 0.2s;
        cursor: pointer;
        text-decoration: none;
    }
    .btn-m.cancel {
        background: #dc3545;
    }
    .btn-m:hover {
        background: #0056b3;
    }
    .btn-m.cancel:hover {
        background: #a71d2a;
    }
    @media (max-width: 600px) {
        .monitoramento-container { padding: 10px 2px; }
        .form-group { flex-direction: column; gap: 8px; }
        .form-group label { width: 100%; }
    }
</style>
<div class="monitoramento-container">
    <h2>Rotina de Monitoramento Inicial</h2>

    <!-- Dados do Aluno -->
    @if(isset($aluno))
    <table class="dados-aluno">
        <thead>
            <tr>
                <th>RA</th>
                <th>Nome</th>
                <th>Idade</th>
                <th>Série</th>
                <th>Período</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $aluno->ra ?? '-' }}</td>
                <td>{{ $aluno->nome ?? '-' }}</td>
                <td>{{ $aluno->idade ?? '-' }}</td>
                <td>{{ $aluno->serie_desc ?? '-' }}</td>
                <td>{{ $aluno->periodo ?? '-' }}</td>
            </tr>
        </tbody>
    </table>
    @endif

    <!-- Dados Gerais -->
    <form method="POST" action="#">
        @csrf
        <div class="form-group">
            <label for="escola">Escola:</label>
            <input type="text" id="escola" name="escola" value="{{ old('escola') }}">
            <label for="turma">Turma:</label>
            <input type="text" id="turma" name="turma" value="{{ old('turma') }}">
        </div>
        <div class="form-group">
            <label for="ano">Ano/Série:</label>
            <input type="text" id="ano" name="ano" value="{{ old('ano') }}">
            <label for="data_nasc">Data de Nascimento:</label>
            <input type="date" id="data_nasc" name="data_nasc" value="{{ old('data_nasc') }}">
        </div>
        <div class="form-group">
            <label for="periodo_inicial">Período Inicial:</label>
            <input type="date" id="periodo_inicial" name="periodo_inicial" value="{{ old('periodo_inicial') }}">
            <label for="periodo_final">Período Final:</label>
            <input type="date" id="periodo_final" name="periodo_final" value="{{ old('periodo_final') }}">
        </div>

        <!-- Atividades -->
        <table class="atividades-table">
            <thead>
                <tr>
                    <th>Atividade</th>
                    <th>Data</th>
                    <th>Realizada?</th>
                    <th>Observações</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>ECM01 - A mágica da gentileza</td>
                    <td><input type="date" name="atividade1_data" value="{{ old('atividade1_data') }}"></td>
                    <td><select name="atividade1_realizada"><option value="">Selecione</option><option value="sim">Sim</option><option value="nao">Não</option></select></td>
                    <td><input type="text" name="atividade1_obs" value="{{ old('atividade1_obs') }}"></td>
                </tr>
                <tr>
                    <td>ECM02 - A mágica do brincar</td>
                    <td><input type="date" name="atividade2_data" value="{{ old('atividade2_data') }}"></td>
                    <td><select name="atividade2_realizada"><option value="">Selecione</option><option value="sim">Sim</option><option value="nao">Não</option></select></td>
                    <td><input type="text" name="atividade2_obs" value="{{ old('atividade2_obs') }}"></td>
                </tr>
                {{-- ECM03 (view) / ECP03 (banco) removido por regra de negócio: não exibir nem permitir marcação desta atividade para o eixo Comportamento --}}
                <tr>
                    <td>ECM04 - A mágica do cuidar</td>
                    <td><input type="date" name="atividade4_data" value="{{ old('atividade4_data') }}"></td>
                    <td><select name="atividade4_realizada"><option value="">Selecione</option><option value="sim">Sim</option><option value="nao">Não</option></select></td>
                    <td><input type="text" name="atividade4_obs" value="{{ old('atividade4_obs') }}"></td>
                </tr>
                <tr>
                    <td>ECM05 - A mágica do aprender</td>
                    <td><input type="date" name="atividade5_data" value="{{ old('atividade5_data') }}"></td>
                    <td><select name="atividade5_realizada"><option value="">Selecione</option><option value="sim">Sim</option><option value="nao">Não</option></select></td>
                    <td><input type="text" name="atividade5_obs" value="{{ old('atividade5_obs') }}"></td>
                </tr>
                <tr>
                    <td>ECM06 - Expressão lúdica</td>
                    <td><input type="date" name="atividade6_data" value="{{ old('atividade6_data') }}"></td>
                    <td><select name="atividade6_realizada"><option value="">Selecione</option><option value="sim">Sim</option><option value="nao">Não</option></select></td>
                    <td><input type="text" name="atividade6_obs" value="{{ old('atividade6_obs') }}"></td>
                </tr>
            </tbody>
        </table>

        <div class="form-group">
            <label for="obs_finais">Observações Finais:</label>
            <input type="text" id="obs_finais" name="obs_finais" value="{{ old('obs_finais') }}" style="width:100%;">
        </div>

        <div class="btn-group">
            <button type="submit" class="btn-m">Salvar</button>
            <a href="{{ route('index') }}" class="btn-m cancel">Cancelar</a>
        </div>
    </form>
</div>
@endsection
                <tr>
                    <td>{{ $aluno->alu_ra ?? '-' }}</td>
                    <td>{{ $aluno->alu_nome ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($aluno->alu_dtnasc)->age ?? '-' }} anos</td>
                    <td>
                        <a href="#" class="btn btn-primary btn-sm">Acessar</a>
                    </td>
                </tr>
            </tbody>
        </table>
    @else
        <div class="alert alert-warning">Nenhum estudante selecionado.</div>
    @endif

    <!-- Botão Voltar -->
    <a href="{{ route('index') }}" class="btn btn-secondary mt-3">Voltar</a>
</div>
@endsection
