<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotificacionRegistroConfirmado extends Notification
{
    use Queueable;
    public $registrado;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($registrado)
    {
        $this->registrado = $registrado;
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
                ->subject('Ruta 365 - Tu cuenta ha sido activada')
                ->markdown('emails.registro-confirmado', [
                    'registrado' => $this->registrado,
                    'linkRespaldo' => route('mailingRespaldo.registro-confirmado',[md5($this->registrado->id)])]);
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
