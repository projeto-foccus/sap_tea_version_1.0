<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            margin: 0;
            padding: 0;
        }
        .logo-top-left {
            position: absolute;
            top: 10mm;
            left: 10mm;
            width: 50mm;
            height: 50mm;
        }
        .logo-bottom-right {
            position: absolute;
            bottom: 10mm;
            right: 10mm;
            width: 50mm;
            height: 50mm;
        }
        .logo-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 40mm;
            height: 40mm;
        }
    </style>
</head>
<body>
    <img src="{{ asset('img/logogando.png') }}" alt="Logo Superior Esquerda" class="logo-top-left">
    <img src="{{ asset('img/logo_baixo.png') }}" alt="Logo Inferior Direita" class="logo-bottom-right">
    <img src="{{ asset('img/logo_sap.png') }}" alt="Logo Transparente Central" class="logo-center">
</body>
</html>
