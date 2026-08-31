<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ApiResetPasswordNotification extends Notification
{
    public $token;
    public $email;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = config('app.frontend_url') . "/admin/reset?token={$this->token}&email=" . urlencode($this->email);

        return (new MailMessage)
            ->subject('Reset your password')
            ->line('Click below to reset your password:')
            ->action('Reset Password', $url)
            ->line('If you did not request a password reset, no action is required.');
    }
}
