<?php

namespace App\Notifications;

// use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PearContact extends Notification
{
    // use Queueable;

    public $name;
    public $contact_info;
    public $reason;
    public $instagram;
    public $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($name, $contact_info, $reason, $instagram, $message)
    {
        $this->name = $name;
        $this->contact_info = $contact_info;
        $this->reason = $reason;
        $this->instagram = $instagram;
        $this->message = $message;
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
        $mail = (new MailMessage)
                    ->subject('Contact Request')
                    ->from('noreply@portraitpear.photography', 'Portrait Pear')
                    ->greeting("$this->name has sent you a contact request!")
                    ->line("They're reaching out about $this->reason.");

        if ($this->reason = 'photography' && !is_null($this->instagram)) {
            $mail = $mail->action('View Instagram', "https://www.instagram.com/$this->instagram");
        }

        $mail = $mail->line("You can contact them at $this->contact_info")
                        ->line('')
                        ->line($this->message);

        return $mail;
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
