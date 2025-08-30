<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
    ];

    protected function tokensMatch($request)
    {
        $token = $this->getTokenFromRequest($request);
        $sessionToken = $request->session()->token();

        \Log::info('Verificando CSRF Token', [
            'session_token' => $sessionToken,
            'request_token' => $token,
            'matches' => is_string($sessionToken) && is_string($token) && hash_equals($sessionToken, $token)
        ]);

        return is_string($sessionToken) &&
               is_string($token) &&
               hash_equals($sessionToken, $token);
    }
}
