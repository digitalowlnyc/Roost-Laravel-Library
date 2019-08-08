<?php

namespace Roost\LaravelTools\Laravel\Notifications;

use Roost\LaravelTools\Helpers\AppHelper;
use Roost\LaravelTools\Helpers\EnvHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Roost\LaravelTools\Laravel\Notifications\Channels\FileChannel;
use Roost\LaravelTools\Laravel\Notifications\Channels\MacNotificationsChannel;

class AdminAlert extends Notification
{
    use Queueable;

	private $subject;
	private $message;

	/** @var string */
	private $level;

	/**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $subject, string $message = null, $level = "CRITICAL")
    {
        $this->message = $message;
        $this->subject = $subject;
        $this->level = $level;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $channels = ["mail", FileChannel::class];

        if(AppHelper::isDebuggingEnabled() && EnvHelper::is("DEVELOPMENT_ENVIRONMENT", "OSX")) {
        	$channels[] = MacNotificationsChannel::class;
		}

		return $channels;
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
					->subject("Alert: Admin [" . $this->level . "]")
                    ->line($this->message);
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
            "message" => $this->message,
			"subject" => $this->subject,
			"level" => $this->level,
			"type" => "AdminAlert"
        ];
    }
}
