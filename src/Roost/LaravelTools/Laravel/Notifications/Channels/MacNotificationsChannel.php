<?php

namespace Roost\LaravelTools\Laravel\Notifications\Channels;

use Illuminate\Notifications\Notification;

class MacNotificationsChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toArray($notifiable);

        $title = $message["subject"];
        $text = $message["message"];

        shell_exec("osascript -e 'display notification \"" . $text . "\" with title \"" . $title . "\"'");
    }
}