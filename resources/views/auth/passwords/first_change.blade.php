<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Definir Nova Senha</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="page">
        <div class="formLogin">
            <h2>Defina sua nova senha</h2>
            <form method="POST" action="{{ url('/password/first-change') }}">
                @csrf
                <label for="password">Nova Senha</label>
                <input type="password" name="password" id="password" placeholder="Digite sua nova senha" required minlength="6">
                <label for="password_confirmation">Confirme a Nova Senha</label>
                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirme sua nova senha" required minlength="6">
                <button class="btn" type="submit">Salvar Nova Senha</button>
            </form>
            @if ($errors->any())
                <div class="notificacao erro">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</body>
</html>
