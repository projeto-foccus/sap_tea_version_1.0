<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha - SAP-TEA</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="page">
        <div class="formLogin">
            <img src="{{ asset('img/logo_sap.png') }}" alt="Logo SAP-TEA" class="logoSap">
            <h2>Redefinir Senha</h2>
            <p class="login-instrucoes">Digite seu e-mail institucional, a nova senha e confirme para redefinir.</p>
            <form method="POST" action="{{ route('password.update') }}" class="login-form">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="form-group">
                    <label for="email_func">E-mail institucional</label>
                    <input type="email" name="email_func" id="email_func" placeholder="Digite seu e-mail" required autofocus value="{{ $email_func ?? old('email_func') }}">
                </div>
                <div class="form-group">
                    <label for="password">Nova senha</label>
                    <input type="password" name="password" id="password" placeholder="Nova senha" required>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirme a nova senha</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirme a nova senha" required>
                </div>
                <div class="form-group" style="margin-bottom: 10px;">
                    <input type="checkbox" id="mostrarSenha" onclick="mostrarOcultarSenha()">
                    <label for="mostrarSenha" style="font-size:0.95em;cursor:pointer;">Mostrar senha</label>
                </div>
                <button class="btn btn-acesso" type="submit">Redefinir senha</button>
                <script>
                    function mostrarOcultarSenha() {
                        var senha = document.getElementById('password');
                        var confirma = document.getElementById('password_confirmation');
                        if(document.getElementById('mostrarSenha').checked){
                            senha.type = 'text';
                            confirma.type = 'text';
                        } else {
                            senha.type = 'password';
                            confirma.type = 'password';
                        }
                    }
                </script>
                <div class="login-links">
                    <a href="{{ route('login') }}" class="link-senha">Voltar ao login</a>
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
        .login-instrucoes {
            font-size: 1.05em;
            color: #333;
            margin-top: 8px;
            margin-bottom: 0;
            text-align:center;
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
