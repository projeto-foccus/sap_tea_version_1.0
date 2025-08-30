<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Exibe o formulário de login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Realiza o login do funcionário
    // Login padrão: email + senha
    public function login(Request $request)
    {
        $data = $request->validate([
            'email_func' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (auth()->guard('funcionario')->attempt([
            'email_func' => $data['email_func'],
            'password' => $data['password'],
        ], $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/index'); // ou rota principal
        }

        return back()->withErrors([
            'email_func' => 'E-mail ou senha inválidos.',
        ])->withInput();
    }

    // Exibe o formulário de recuperação de senha
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    // Envia o e-mail de redefinição de senha
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email_func' => 'required|email']);
        $status = \Password::broker('funcionarios')->sendResetLink(
            ['email_func' => $request->email_func]
        );
        return $status === \Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email_func' => __($status)]);
    }

    // Exibe o formulário de redefinição de senha
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset', [
            'token' => $token,
            'email_func' => $request->email_func
        ]);
    }

    // Processa a redefinição de senha
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email_func' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = \Password::broker('funcionarios')->reset(
            $request->only('email_func', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->precisa_trocar_senha = 0;
                $user->save();
            }
        );

        return $status == \Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email_func' => [__($status)]]);
    }

    // Exibe o formulário de primeiro acesso
    public function showPrimeiroAcessoForm()
    {
        return view('auth.primeiro_acesso');
    }

    // Fluxo de primeiro acesso: email + CPF
    public function primeiroAcesso(Request $request)
    {
        $data = $request->validate([
            'email_func' => ['required', 'email'],
            'cpf' => ['required'],
        ]);

        $funcionario = \App\Models\Funcionario::where('email_func', $data['email_func'])->first();

        if (!$funcionario) {
            return back()->withErrors(['email_func' => 'E-mail não encontrado.'])->withInput();
        }

        if (!isset($funcionario->func_cpf) || $funcionario->func_cpf !== $data['cpf']) {
            return back()->withErrors(['cpf' => 'CPF inválido para este e-mail.'])->withInput();
        }

        if (isset($funcionario->precisa_trocar_senha) && $funcionario->precisa_trocar_senha) {
            $request->session()->put('funcionario_id_primeiro_acesso', $funcionario->func_id);
            return redirect()->route('password.first.change');
        }

        return back()->withErrors(['email_func' => 'Este usuário já realizou o primeiro acesso. Use a tela de login normal.'])->withInput();
    }

    // Logout do funcionário
    public function logout(Request $request)
    {
        auth()->guard('funcionario')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // Exibe o formulário de troca de senha do primeiro acesso
    public function showFirstChangeForm(Request $request)
    {
        // Verifica se há um funcionário em primeiro acesso na sessão
        $funcionarioId = $request->session()->get('funcionario_id_primeiro_acesso');
        if (!$funcionarioId) {
            return redirect()->route('login')->withErrors(['email_func' => 'Acesso não autorizado. Realize o login.']);
        }
        return view('auth.passwords.first_change');
    }

    // Processa a troca de senha do primeiro acesso
    public function processFirstChange(Request $request)
    {
        $funcionarioId = $request->session()->get('funcionario_id_primeiro_acesso');
        if (!$funcionarioId) {
            return redirect()->route('login')->withErrors(['email_func' => 'Acesso não autorizado.']);
        }
        $request->validate([
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        $funcionario = \App\Models\Funcionario::find($funcionarioId);
        if (!$funcionario) {
            return redirect()->route('login')->withErrors(['email_func' => 'Funcionário não encontrado.']);
        }
        // Atualiza senha e marca que não precisa mais trocar
        $funcionario->password = bcrypt($request->password);
        $funcionario->precisa_trocar_senha = false;
        $funcionario->save();
        // Limpa sessão de primeiro acesso
        $request->session()->forget('funcionario_id_primeiro_acesso');
        // Redireciona para login com mensagem
        return redirect()->route('login')->with('status', 'Senha definida com sucesso! Faça login normalmente.');
    }
}
