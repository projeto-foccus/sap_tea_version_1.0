@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário</title>
    <link rel="stylesheet" href="{{ asset('css/inventario.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
</head>

<body>

    <form id="form" method="POST" action="{{ route('inserir_inventario', ['id' => $aluno->alu_id]) }}">
        @csrf
        <input type="hidden" name="aluno_id" value="{{$aluno->alu_id }}">

        <div class="menu">
            <img src="{{ asset('img/LOGOTEA.png') }}" alt="Logo" class="logo">
            <img src="{{ asset('img/logo_sap.png') }}" alt="Logo SAP" class="logo-right">

            <h1>SONDAGEM PEDAGÓGICA 1 - INICIAL</h1>
            <p>Secretaria de Educação do Município</p>

            <div class="fields">
                <p>Data de inicio inventário:
                    <?php
                    $data_atual = date("d-m-Y");
                    echo '<input name = "data_inicio_inventario" type="text" value="' . $data_atual . '" readonly style = "width: 80px"> ';
                    ?>
                </p>

                <p>Orgão: <input type="text" style="width: 300px;" value="{{$aluno->org_razaosocial}}" readonly></p>
                <p>Escola: <input type="text" style="width: 300px;" value="{{$aluno->esc_razao_social}}" readonly></p>
                <p>Nome do Aluno: <input type="text" style="width: 250px;" value="{{$aluno->alu_nome}}" readonly></p>
                <p>Data de Nascimento: <input type="date" value="{{$aluno->alu_dtnasc}}" readonly>
                    Idade: <input value="{{ \Carbon\Carbon::parse($aluno->alu_dtnasc)->age }} - anos" readonly type="text" min="0" style="width: 50px;"></p>
                <p>Ano/Série: <input type="text" style="width: 150px;" value="{{$aluno->serie_desc}}" readonly>
                    Turma: <input value="{{$aluno->fk_cod_valor_turma}}" type="text" style="width: 120px;" readonly>
                    Ano/Série: <input type="text" style="width: 250px;" value="{{$aluno->desc_modalidade}}" readonly></p>
            </div>

            <div class="support">
                <p><strong>Responsável pelo preenchimento:</strong></p>
                <p><input type="radio" name="responsavel" class="radio-toggle" value = "1"> Professor de sala Regular</p>
                <p><input type="radio" name="responsavel" class="radio-toggle" value = "0"> Professor do Atendimento Educacional Especializado (AEE)</p>

                <p><strong>Assinale o nível de suporte necessário para o estudante:</strong></p>
                <p><input type="radio" name="suporte" class="radio-toggle" value = "1"> Nível 1 de Suporte - Exige pouco apoio</p>
                <p><input type="radio" name="suporte" class="radio-toggle" value = "2"> Nível 2 de Suporte - Exige apoio substancial</p>
                <p><input type="radio" name="suporte" class="radio-toggle" value = "3"> Nível 3 de Suporte - Exige apoio muito substancial</p>

                <p><strong>Assinale a forma de comunicação utilizada pelo estudante:</strong></p>
                <p><input type="radio" name="comunicacao" class="radio-toggle" value = "1"> Comunicação verbal</p>
                <p><input type="radio" name="comunicacao" class="radio-toggle" value = "2"> Comunicação não verbal com uso de métodos alternativos de comunicação</p>
                <p><input type="radio" name="comunicacao" class="radio-toggle" value = "3"> Comunicação não Verbal</p>
            </div>
        </div>

        <!-- Tabela para Eixo Comunicação/Linguagem -->
        <table>
            <thead>
                <tr>
                    <th>INVENTÁRIO DE HABILIDADES - EIXO COMUNICAÇÃO/LINGUAGEM</th>
                    <th>Sim</th>
                    <th>Não</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 1; $i <= 32; $i++)
                    <tr>
                        <td>{{ /* Adicione aqui as perguntas correspondentes */ }}</td>
                        <td><input type="radio" name="{{ 'ecm' . sprintf('%02d', $i) }}" id="{{ 'ecm' . sprintf('%02d', $i) . '_s' }}" value = "1"></td>
                        <td><input type="radio" name="{{ 'ecm' . sprintf('%02d', $i) }}" id="{{ 'ecm' . sprintf('%02d', $i) . '_n' }}" value = "0"></td>
                    </tr>
                @endfor
            </tbody>
        </table>

        <!-- Tabela para Eixo Comportamento -->
        <table>
            <thead>
                <tr>
                    <th>INVENTÁRIO DE HABILIDADES - EIXO COMPORTAMENTO</th>
                    <th>Sim</th>
                    <th>Não</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 1; $i <= 17; $i++)
                    <tr>
                        <td>{{ /* Adicione aqui as perguntas correspondentes */ }}</td>
                        <td><input type="radio" name="{{ 'ecp' . sprintf('%02d', $i) }}" id="{{ 'ecp' . sprintf('%02d', $i) . '_s' }}" value = "1"></td>
                        <td><input type="radio" name="{{ 'ecp' . sprintf('%02d', $i) }}" id="{{ 'ecp' . sprintf('%02d', $i) . '_n' }}" value = "0"></td>
                    </tr>
                @endfor
            </tbody>
        </table>

        <!-- Tabela para Eixo Interação Socioemocional -->
        <table>
            <thead>
                <tr>
                    <th>INVENTÁRIO DE HABILIDADES - EIXO INTERAÇÃO SOCIOEMOCIONAL</th>
                    <th>Sim</th>
                    <th>Não</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 1; $i <= 18; $i++)
                    <tr>
                        <td>{{ /* Adicione aqui as perguntas correspondentes */ }}</td>
                        <td><input type="radio" name="{{ 'eis' . sprintf('%02d', $i) }}" id="{{ 'eis' . sprintf('%02d', $i) . '_s' }}" value = "1"></td>
                        <td><input type="radio" name="{{ 'eis' . sprintf('%02d', $i) }}" id="{{ 'eis' . sprintf('%02d', $i) . '_n' }}" value = "0"></td>
                    </tr>
                @endfor
            </tbody>
        </table>

        <!-- Botões -->
        <div class="button-group">
            <button type="submit">Salvar</button>
            <a href="{{ route('index') }}">Cancelar</a>

            <!-- Botão para gerar PDF -->
            <!-- O código JavaScript para gerar PDF deve ser adicionado aqui -->
        </div>

    </form>

    <!-- Script para validação do formulário -->
    <!-- Adicione o script JavaScript conforme discutido anteriormente -->
    <!-- Exemplo de validação -->
    <!-- 
    document.getElementById('form').addEventListener('submit', function(e) {
        let isValid = true;
        const errorMessage = [];

        const requiredFields = [
            'responsavel', 
            'suporte', 
            'comunicacao',
            // Adicione os nomes dos campos obrigatórios aqui
        ];

        requiredFields.forEach(function(field) {
            const input = document.querySelector(`input[name="${field}"]:checked`);
            if (!input) {
                isValid = false;
                errorMessage.push(`O campo ${field} é obrigatório.`);
            }
        });

        if (!isValid) {
            e.preventDefault(); // Impede o envio do formulário se houver campos inválidos
            alert(errorMessage.join('\n')); // Exibe mensagens de erro
        }
    });
    -->

    <!-- Importação das bibliotecas para geração de PDF -->
    <!-- ... (seu código para PDF) -->

    <!-- Script para geração do PDF -->
    <!-- Código JavaScript para gerar PDF deve ser adicionado aqui -->

</body>

</html>

@endsection
