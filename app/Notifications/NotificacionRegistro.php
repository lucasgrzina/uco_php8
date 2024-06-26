<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotificacionRegistro extends Notification
{
    use Queueable;
    public $registrado;
    public $locale;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($registrado,$locale)
    {
        $this->registrado = $registrado;
        $this->locale = $locale;
        app()->setLocale($locale);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        return (new MailMessage)
                ->subject(trans('emails.registro.subject'))
                ->markdown('emails.registro', ['registrado' => $this->registrado]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
