<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends BaseVerifyEmail
{
    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject('Verifica tu correo - reservApp')
            ->greeting('¡Bienvenido a reservApp! 🎉')
            ->line('Antes de comenzar, necesitamos que confirmes tu dirección de correo.')
            ->action('Verificar correo', $url)
            ->line('Gracias por confiar en reservApp, la plataforma para gestionar tus citas en talleres.')
            ->salutation('¡Nos vemos pronto!');
    }
}
