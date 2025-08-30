<?php

namespace App\Models;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Funcionario extends Authenticatable
{
    use Notifiable;

    public function getAuthIdentifierName()
    {
        return 'email_func';
    }
    protected $table = 'funcionario';
    protected $primaryKey = 'func_id';

    protected $fillable = [
        'email_func', 'cpf', 'password', // adicione outros campos necessários
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Usar o campo email_func como email para autenticação e reset.
     */
    public function getEmailForPasswordReset()
    {
        return $this->email_func;
    }

    // Customização do envio do e-mail de reset para debug
    public function sendPasswordResetNotification($token)
    {
        $resetUrl = url(route('password.reset', ['token' => $token, 'email_func' => $this->email_func], false));
        \Log::info('Enviando e-mail de reset para: ' . $this->email_func . ' | Link: ' . $resetUrl);
        \Notification::route('mail', $this->email_func)
            ->notify(new \App\Notifications\ResetSenhaCustomizada($resetUrl));
    }

    public function tipoFuncao()
    {
        return $this->belongsTo(TipoFuncao::class, 'func_cod_funcao', 'tipo_funcao_id');
    }

    public function turmas()
    {
        return $this->hasMany(Turma::class, 'fk_cod_func', 'func_id');
    }
}
