<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Perfil do Estudante</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
            page-break-after: avoid;
        }
        .field-group {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .field-label {
            font-weight: bold;
            color: #444;
            display: block;
            margin-bottom: 5px;
            font-size: 12px;
        }
        .field-value {
            margin-left: 20px;
            color: #666;
            display: block;
            margin-bottom: 5px;
            font-size: 12px;
        }
        .page-number {
            text-align: right;
            margin-top: 20px;
            font-size: 11px;
            color: #666;
            position: absolute;
            bottom: 20px;
            right: 20px;
        }
        .last-page {
            page-break-after: avoid;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Perfil do Estudante</h1>
        <p>Nome: {{ $aluno->alu_nome ?? 'Não informado' }}</p>
        <p>Data: {{ date('d/m/Y') }}</p>
    </div>

    @foreach($dados as $section => $fields)
        <div class="section">
            <h2 class="section-title">{{ $section }}</h2>
            
            @foreach($fields as $key => $value)
                <div class="field-group">
                    <span class="field-label">{{ str_replace('_', ' ', ucwords($key)) }}:</span>
                    <span class="field-value">{{ $value ?? 'Não informado' }}</span>
                </div>
            @endforeach
        </div>
    @endforeach

    <div class="last-page">
        <div class="page-number">Página {{ count($dados) }}</div>
    </div>
</body>
</html>
