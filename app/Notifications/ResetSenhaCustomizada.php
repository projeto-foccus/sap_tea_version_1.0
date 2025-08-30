<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetSenhaCustomizada extends Notification
{
    use Queueable;

    protected $resetUrl;

    public function __construct($resetUrl)
    {
        $this->resetUrl = $resetUrl;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Recuperação de senha - SAP-TEA')
            ->greeting('Olá!')
            ->line('Recebemos uma solicitação para redefinir sua senha no SAP-TEA.')
            ->action('Redefinir senha', $this->resetUrl)
            ->line('Se você não solicitou a redefinição, ignore este e-mail.')
            ->salutation('Atenciosamente, Equipe SAP-TEA');
    }
}
