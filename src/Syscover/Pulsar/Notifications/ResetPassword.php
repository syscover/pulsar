<?php namespace Syscover\Pulsar\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;


    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail()
    {
        return (new MailMessage)
            ->line(trans('pulsar::pulsar.message_reset_password_notification_01'))
            ->action(trans('pulsar::pulsar.reset_password'), route('showResetForm', ['token' => $this->token]))
            ->line(trans('pulsar::pulsar.message_reset_password_notification_02'));
    }
}
