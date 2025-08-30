@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Inventário</title>
    <style>
        /* ====== CSS de inventario.css ====== */
        /* Estilo geral */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        /* Títulos */
        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #db7a19;
        }
        /* Tabelas */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th,
        td {
            border: 1px solid #dee2e6;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #db7a19;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        /* Inputs */
        input[type="radio"] {
            appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid #db7a19;
            border-radius: 4px;
            display: inline-block;
            position: relative;
            cursor: pointer;
            background-color: #ffffff;
        }
        input[type="radio"]:checked::after {
            content: "X";
            font-size: 16px;
            font-weight: bold;
            color: rgb(14, 14, 14);
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        input[type="radio"]:hover {
            border-color: #db7a19;
        }
        /* ====== CSS de header.css ====== */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .menu {
            background-color: #f4f4f4;
            padding: 15px;
            text-align: center;
            border-bottom: 3px solid #d9534f;
            position: relative;
        }
        .menu h1 {
            margin: 0;
            font-size: 22px;
            color: #d9534f;
        }
        .menu p {
            margin: 5px 0;
            font-size: 14px;
            color: #333;
        }
        .fields, .support {
            font-size: 14px;
            margin-top: 10px;
            text-align: left;
            padding: 10px;
            background-color: #e0e0e0;
            border-radius: 5px;
        }
        .support {
            background-color: #ffffff;
        }
        .menu input[type="text"], .menu input[type="date"], .menu input[type="number"] {
            width: auto;
            padding: 3px;
            margin: 2px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 12px;
        }
        .logo {
            position: absolute;
            top: 10px;
            left: 10px;
            width: 150px;
        }
        .logo-right {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 50px;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/inventario.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
   
</head>
<body>
<div class="menu">
        <img src="{{ asset('img/LOGOTEA.png') }}" alt="Logo" class="logo">
        <img src="{{ asset('img/logo_sap.png') }}" alt="Logo SAP" class="logo-right">

        <h1>SONDAGEM PEDAGÓGICA 1 - INICIAL</h1>
        <div class="fields">
            <p>Orgão: <input type="text" style="width: 300px;" value="{{$alunoDetalhado->org_razaosocial}}" readonly></p>
            <p>Escola: <input type="text" style="width: 300px;" value="{{$alunoDetalhado->esc_razao_social}}" readonly ></p>
            <p>Nome do estudante: <input type="text" style="width: 250px;" value="{{$aluno->alu_nome}}" readonly></p>
            <p>Data de Nascimento: <input type="date" value ="{{$aluno->alu_dtnasc}}" readonly>
                Idade: <input value = "{{ \Carbon\Carbon::parse($aluno->alu_dtnasc)->age }} - anos" readonly type="text" min="0" style="width: 50px;">
            </p>
            <p>Ano/Série: <input type="text" style="width: 150px;" value="{{$alunoDetalhado->serie_desc}}" readonly> 
                Turma: <input value="{{$alunoDetalhado->fk_cod_valor_turma}}" type="text" style="width: 120px;" readonly> 
                Modalidade: <input type="text" style="width: 200px;" value="{{$alunoDetalhado->desc_modalidade}}" readonly>
            </p>
        </div>
      
        <div class="fields">
            <div class="fields">
                <p>Data de inicio inventario:
                    <?php
                    $data_atual = date("d-m-Y");
                    echo '<input name = "data_inicio_inventario" type="text" value="' . $data_atual . '" readonly
                    style = "width: 80px"> ';
                    ?>
                </p>

            
            <p>Orgão: <input type="text" style="width: 300px;" value = "{{$alunoDetalhado->org_razaosocial}}" readonly></p>

            <p>Escola: <input type="text" style="width: 300px;" value = "{{$alunoDetalhado->esc_razao_social}}" readonly ></p>
            <p>Nome do estudante: <input type="text" style="width: 250px;"value = "{{$aluno->alu_nome}}" readonly></p>
            
            <p>Data de Nascimento: <input type="date" value ="{{$aluno->alu_dtnasc}}" readonly>
                 Idade: <input value = "{{ \Carbon\Carbon::parse($aluno->alu_dtnasc)->age }} - anos" readonly type="text" min="0" style="width: 50px;"></p>
            <p>Ano/Série: <input type="text" style="width: 150px;" value = "{{$alunoDetalhado->serie_desc}}" readonly> 
                Turma:
                <input value = "{{$alunoDetalhado->fk_cod_valor_turma}}" type="text" style="width: 120px;" readonly> 
                Ano/Série <input type="text"style="width: 200px;" style="width: 250px;" value = "{{$alunoDetalhado->desc_modalidade}}" readonly></p>
        </div>
        <div class="inventory-data">
            <!-- Seção Responsável e Suporte -->
            <div class="section" style="max-width: 500px; margin: 0 0 16px 0;">
                <table style="width:100%; background: #f9f9f9; border-radius: 8px; box-shadow: 0 2px 8px #0001;">
                    <tr>
                        <th style="text-align:left; width: 50%;">Responsável pelo Preenchimento:</th>
                        <td>
                            @if($preenchimento->professor_responsavel == 1)
                                Professor de sala Regular
                            @else
                                Professor do Atendimento Educacional Especializado (AEE)
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th style="text-align:left;">Nível de Suporte:</th>
                        <td>
                            @switch($preenchimento->nivel_suporte)
                                @case(1) Nível 1 - Pouco apoio @break
                                @case(2) Nível 2 - Apoio substancial @break
                                @case(3) Nível 3 - Apoio muito substancial @break
                            @endswitch
                        </td>
                    </tr>
                    <tr>
                        <th style="text-align:left;">Forma de Comunicação:</th>
                        <td>
                            @switch($preenchimento->nivel_comunicacao)
                                @case(1) Verbal @break
                                @case(2) Não verbal com métodos alternativos @break
                                @case(3) Não verbal @break
                            @endswitch
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Eixo Comunicação/Linguagem -->
            <div class="section">
                <h2>EIXO COMUNICAÇÃO/LINGUAGEM</h2>
                <table>
                    <thead>
                        <tr style="background-color: #A1D9F6;">
                            <th>Pergunta</th>
                            <th>Sim</th>
                            <th>Não</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($Perguntas_eixo_comunicacao as $i => $pergunta)
                            @php 
                                $campo = 'ecm' . sprintf('%02d', $i+1);
                                $valor = $eixoComunicacao->$campo ?? null;
                            @endphp
                            <tr style="background-color: #A1D9F6;">
                                <td>{{ $pergunta }}</td>
                                <td>
                                    <input type="radio" 
                                           class="readonly-radio"
                                           {{ $valor == 1 ? 'checked' : '' }} 
                                           disabled>
                                </td>
                                <td>
                                    <input type="radio" 
                                           class="readonly-radio"
                                           {{ $valor === 0 ? 'checked' : '' }} 
                                           disabled>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Eixo Comportamento -->
            <div class="section">
                <h2>EIXO COMPORTAMENTO</h2>
                <table>
                    <thead>
                        <tr style="background-color: #A1D9F6;">
                            <th>Pergunta</th>
                            <th>Sim</th>
                            <th>Não</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($perguntas_eixo_comportamento as $i => $pergunta)
                            @php 
                                $campo = 'ecp' . sprintf('%02d', $i+1);
                                $valor = $eixoComportamento->$campo ?? null;
                            @endphp
                            <tr style="background-color: #FFEB3B;">
                                <td>{{ $pergunta }}</td>
                                <td>
                                    <input type="radio" 
                                           class="readonly-radio"
                                           {{ $valor == 1 ? 'checked' : '' }} 
                                           disabled>
                                </td>
                                <td>
                                    <input type="radio" 
                                           class="readonly-radio"
                                           {{ $valor === 0 ? 'checked' : '' }} 
                                           disabled>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Eixo Socioemocional -->
            <div class="section">
                <h2>EIXO INTERAÇÃO SOCIOEMOCIONAL</h2>
                <table>
                    <thead>
                        <tr style="background-color: #A1D9F6;">
                            <th>Pergunta</th>
                            <th>Sim</th>
                            <th>Não</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($eixo_int_socio_emocional as $i => $pergunta)
                            @php 
                                $campo = 'eis' . sprintf('%02d', $i+1);
                                $valor = $eixoSocioEmocional->$campo ?? null;
                            @endphp
                            <tr style="background-color: #d7EAD9 !important;">
                                <td>{{ $pergunta }}</td>
                                <td>
                                    <input type="radio" 
                                           class="readonly-radio"
                                           {{ $valor == 1 ? 'checked' : '' }} 
                                           disabled>
                                </td>
                                <td>
                                    <input type="radio" 
                                           class="readonly-radio"
                                           {{ $valor === 0 ? 'checked' : '' }} 
                                           disabled>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
      <div class="button-group" >
          
      <a href="{{ route('index') }}" class="btn btn-danger">Cancelar</a>
      <button type="button" class="pdf-button">Gerar PDF</button>

        </div>
 <!-- Importação das bibliotecas (deixe antes do script) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
document.querySelector(".pdf-button").addEventListener("click", function() {
    const { jsPDF } = window.jspdf;
    const element = document.querySelector('.menu');

    html2canvas(element, {
        scale: 0.9,
        useCORS: true
    }).then(canvas => {
        const imgData = canvas.toDataURL("image/png");
        const pdf = new jsPDF("p", "mm", "a4");
        const imgWidth = 210;
        const pageHeight = 297;
        const imgHeight = (canvas.height * imgWidth) / canvas.width;

        let y = 0;
        while (y < imgHeight) {
            pdf.addImage(imgData, "PNG", 0, y * -1, imgWidth, imgHeight);
            y += pageHeight;
            if (y < imgHeight) pdf.addPage();
        }

        // Pegando o nome do aluno do input (garante que é o mesmo mostrado na tela)
        let nomeAluno = document.querySelector('input[value="{{ $aluno->alu_nome }}"]').value || "{{ $aluno->alu_nome }}";
        // Remove acentos e caracteres especiais, troca espaços por _
        nomeAluno = nomeAluno
            .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-zA-Z0-9]/g, '_')
            .replace(/_+/g, '_')
            .replace(/^_+|_+$/g, '');

        // Data no formato DD-MM-AAAA
        const hoje = new Date();
        const dia = String(hoje.getDate()).padStart(2, '0');
        const mes = String(hoje.getMonth() + 1).padStart(2, '0');
        const ano = hoje.getFullYear();
        const dataAtual = `${dia}-${mes}-${ano}`;

        // Nome do arquivo
        const nomeArquivo = `Inventario_${nomeAluno}_${dataAtual}.pdf`;

        pdf.save(nomeArquivo);
    }).catch(error => console.error("Erro ao gerar PDF:", error));
});
</script>

   


</body>
</html>
@endsection


