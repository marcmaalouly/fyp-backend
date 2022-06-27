<?php

namespace App\Notifications;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendZoomURLNotification extends Notification
{
    use Queueable;

    protected $data;
    protected $template;
    protected $custom_message;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(array $data, EmailTemplate $template = null, $custom_message = null)
    {
        $this->data = $data;
        $this->template = $template;
        $this->custom_message = $custom_message;
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
        $message = $this->checkForTemplate();

        return (new MailMessage)
                    ->subject('Zoom Meeting')
                    ->line($message)
                    ->action('Zoom Meeting', $this->data['join_url']);
    }

    protected function checkForTemplate()
    {
        if ($this->template) {
            return $this->template->message;
        }

        if ($this->custom_message) {
            return $this->custom_message;
        }
        return "You have been invited by " . auth()->user()->full_name . " for a zoom meeting.";
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
