<?php

namespace Roost\LaravelTools\Laravel\Notifications;

use Roost\LaravelTools\Laravel\Models\AppBaseModel;
use Roost\LaravelTools\Laravel\Models\Traits\ModelUpdateCountTrait;

class AdminDatabaseNotification extends AppBaseModel {
	use ModelUpdateCountTrait;

	public $table = "admin_notifications";

		protected $guarded = ["id"];

}