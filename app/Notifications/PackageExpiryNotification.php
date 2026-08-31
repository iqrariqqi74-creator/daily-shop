<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PackageExpiryNotification extends Notification
{
    use Queueable;

    public $customer;

    public function __construct($customer)
    {
        $this->customer = $customer;

    }

    public function via($notifiable)
    {
        return ['mail', 'sms']; // 'sms' is a custom channel you'll define
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Package is About to Expire')
            ->line("Dear {$this->customer->name},")
            ->line("Your current package \"{$this->customer->package->name}\" will expire on {$this->customer->end_date_time}.")
            ->line("Please recharge or renew your package to continue the service.")
            ->line("Thank you.");
    }

    public function toSms($notifiable)
    {
        return "Dear {$this->customer->name}, your package \"{$this->customer->package->name}\" will expire on {$this->customer->end_date_time}. Please recharge.";
    }
}
