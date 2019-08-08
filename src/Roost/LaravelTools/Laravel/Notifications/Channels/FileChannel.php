<?php

namespace Roost\LaravelTools\Laravel\Notifications\Channels;

use Illuminate\Notifications\Notification;

class FileChannel
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

        $message = json_encode($message, JSON_PRETTY_PRINT);

        file_put_contents("notifications.log", date("r") . ": " . $message . PHP_EOL, FILE_APPEND);

        // Send notification to the $notifiable instance...
    }
}