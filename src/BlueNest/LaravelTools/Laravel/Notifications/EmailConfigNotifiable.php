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

    public function __construct($configKey, $configFile = 'mail') {
        $configKey = $configFile . '.notification-groups.' . $configKey;

        if(!Config::has($configKey)) {
            throw new \Exception('Config does not contain key: ' . $configKey);
        }

        $emails = config($configKey);

        $allConfigKey = $configFile . '.notification-groups.' . 'all';
        if(Config::has($allConfigKey)) {
            $emails = array_merge($emails, config($allConfigKey));
        }

        $this->email = $emails;
    }
}