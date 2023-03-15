<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class MailRequestPasswordToken extends Notification
{
    use Queueable;
    public $clave;
    public $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($clave,$user)
    {
        $this->clave = $clave;
        $this->user = $user;
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
                ->subject(trans('emails.recuperar.subject'))
                ->markdown('emails.reset-password',[
                    'clave' => $this->clave,
                    'user' => $this->user,
                    'linkRespaldo' => route('mailingRespaldo.registro',[md5($this->user->id)])
                ]);
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
