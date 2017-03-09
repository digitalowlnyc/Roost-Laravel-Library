<?php
/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace BlueNest\LaravelTools\Laravel\Notifications;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Config;

class EmailConfigNotifiable {
    use Notifiable;

    public $email;

    public function __construct($configKey) {
        if(!Config::has($configKey)) {
            throw new \Exception('Config does not contain key: ' . $configKey);
        }

        if(!Config::has('all')) {
            throw new \Exception('Config does not contain "all" key');
        }

        $emails = array_merge(config($configKey), config('mail.notification-groups.all'));

        $this->email = $emails;
    }
}