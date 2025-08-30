<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sondagem Pedagógica</title>
    <style>
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
    right: 10px; /* Posiciona no lado direito */
    width: 50px; /* Ajuste o tamanho conforme necessário */
}
    </style>
</head>
<body>
    <div class="menu">
    <img src="{{ asset('img/LOGOTEA.png') }}" alt="Logo" class="logo">
    <img src="{{ asset('img/logo_sap.png') }}" alt="Logo SAP" class="logo-right">

        <h1>SONDAGEM PEDAGÓGICA 1 - INICIAL</h1>
        <p>Secretaria de Educação do Município</p>
        <div class="fields">
            <p>Data: <input type="date"></p>
            <p>Secretaria de educação do município: <input type="text"></p>
            <p>Escola: <input type="text"></p>
            <p>Nome do Aluno: <input type="text"></p>
            <p>Data de Nascimento: <input type="date"> Idade: <input type="number" min="0" style="width: 50px;"></p>
            <p>Ano/Série: <input type="text" style="width: 50px;"> Turma: <input type="text" style="width: 50px;"> Período: <input type="text" style="width: 50px;"></p>
        </div>
        <div class="support">
            <p><strong>Responsável pelo preenchimento:</strong></p>
            <p><input type="radio" name="responsavel" class="radio-toggle"> Professor de sala Regular</p>
            <p><input type="radio" name="responsavel" class="radio-toggle"> Professor do Atendimento Educacional Especializado (AEE)</p>
            <p><strong>Assinale o nível de suporte necessário para o estudante:</strong></p>
            <p><input type="radio" name="suporte" class="radio-toggle"> Nível 1 de Suporte - Exige pouco apoio</p>
            <p><input type="radio" name="suporte" class="radio-toggle"> Nível 2 de Suporte - Exige apoio substancial</p>
            <p><input type="radio" name="suporte" class="radio-toggle"> Nível 3 de Suporte - Exige apoio muito substancial</p>
            <p><strong>Assinale a forma de comunicação utilizada pelo estudante:</strong></p>
            <p><input type="radio" name="comunicacao" class="radio-toggle"> Comunicação verbal</p>
            <p><input type="radio" name="comunicacao" class="radio-toggle"> Comunicação não verbal com uso de métodos alternativos de comunicação</p>
            <p><input type="radio" name="comunicacao" class="radio-toggle"> Comunicação não Verbal</p>
        </div>
    </div>
    <script src="{{ asset('js/script.js') }}"></script>


</body>
</html>
