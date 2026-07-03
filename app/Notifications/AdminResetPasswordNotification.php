<?php

namespace App\Notifications;

use App\Mail\AdminResetPasswordMail;
use Illuminate\Notifications\Notification;

class AdminResetPasswordNotification extends Notification
{
    public function __construct(public string $token) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): AdminResetPasswordMail
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return new AdminResetPasswordMail($notifiable->name, $url);
    }
}
