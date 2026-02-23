<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends BaseResetPassword
{
    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Restablece tu contraseña - reservApp')
            ->greeting('Hola ' . $notifiable->name . ' 👋')
            ->line('Recibimos una solicitud para restablecer tu contraseña en reservApp.')
            ->action('Restablecer contraseña', $url)
            ->line('Si no solicitaste este cambio, puedes ignorar este mensaje.')
            ->salutation('¡Gracias por usar reservApp! 🔧');
    }
}
