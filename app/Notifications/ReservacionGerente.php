<?php

namespace App\Notifications;

use App\Models\Reservacion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservacionGerente extends Notification
{
    use Queueable;
    public $reservacion;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Reservacion $reservacion)
    {
        $this->reservacion=$reservacion;
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
                    ->subject('Reservación de turismo GAD-SALCEDO')
                    ->line('Cliente: '.$this->reservacion->user->email)
                    ->line('Desea realizar una reservacion de:')
                    ->line('Turismo '.$this->reservacion->turismo->nombre)
                    ->line('Estado de reservacion')
                    ->line('Fecha inicio: '.$this->reservacion->fecha_inicio)
                    ->line('Fecha final: '.$this->reservacion->fecha_final)
                    ->line('Cantidad  de personas: '.$this->reservacion->cantidad_personas)
                    ->line('Gracias por usar nuestra aplicación!');
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
