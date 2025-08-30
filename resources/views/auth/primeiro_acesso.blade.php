<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Primeiro Acesso - SAP-TEA</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="page">
        <div class="formLogin">
            <img src="{{ asset('img/logo_sap.png') }}" alt="Logo SAP-TEA" class="logoSap">
            <div class="login-header">
                <h2>Primeiro acesso</h2>
                <p class="login-instrucoes">Informe seu e-mail institucional e CPF. Após validar, você poderá definir sua senha.</p>
            </div>
            <form method="POST" action="{{ url('/primeiro-acesso') }}" class="login-form">
                @csrf
                <div class="form-group">
                    <label for="email_func">E-mail</label>
                    <input type="email" name="email_func" id="email_func" placeholder="Digite seu e-mail" required autofocus value="{{ old('email_func') }}">
                </div>
                <div class="form-group">
                    <label for="cpf">CPF</label>
                    <input type="text" name="cpf" id="cpf" placeholder="Digite seu CPF" required maxlength="14" value="{{ old('cpf') }}">
                </div>
                <button class="btn btn-acesso" type="submit">Validar primeiro acesso</button>
                <div class="login-links">
                    <a href="{{ route('login') }}" class="link-senha">Voltar para login</a>
                </div>
            </form>
            @if ($errors->any())
                <div class="notificacao erro" id="notificacao">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    <style>
        .login-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .login-instrucoes {
            font-size: 1.05em;
            color: #333;
            margin-top: 8px;
            margin-bottom: 0;
        }
        .login-form {
            margin-top: 10px;
        }
        .form-group {
            margin-bottom: 14px;
        }
        .btn-acesso {
            width: 100%;
            background: #0056b3;
            color: #fff;
            font-weight: bold;
            font-size: 1.1em;
            border-radius: 5px;
            margin-top: 10px;
            margin-bottom: 8px;
        }
        .btn-acesso:hover {
            background: #003a75;
        }
        .login-links {
            text-align: center;
            margin-top: 5px;
        }
        .link-senha {
            color: #0056b3;
            text-decoration: underline;
            font-size: 1em;
            white-space: nowrap;
        }
        .link-senha:hover {
            color: #003a75;
        }
    </style>
</body>
</html>
