<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckFuncionarioFuncao
{
    /**
     * Handle an incoming request.
     */
    /**
     * Middleware para restringir acesso a determinadas funções de funcionário.
     * Administrador (1) tem acesso total.
     * Funções especiais: 6, 7, 13.
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::guard('funcionario')->user();
        if (!$user) {
            abort(401, 'Usuário não autenticado.');
        }

        // Administrador tem acesso total
        if ($user->func_cod_funcao == 1) {
            return $next($request);
        }

        // Funções especiais permitidas
        $funcoesPermitidas = [6, 7, 13];
        if (in_array($user->func_cod_funcao, $funcoesPermitidas)) {
            return $next($request);
        }

        // Log de tentativa negada
        \Log::warning("Acesso negado para user_id {$user->id} com func_cod_funcao {$user->func_cod_funcao}");
        abort(403, 'Você não tem permissão para acessar esta funcionalidade.');
    }
}
