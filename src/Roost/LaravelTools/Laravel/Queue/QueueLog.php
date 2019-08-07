<?php

namespace Roost\LaravelTools\Laravel\Queue;

use Roost\LaravelTools\Laravel\Models\AppBaseModel;
use Roost\LaravelTools\Laravel\Models\Traits\ModelUpdateCountTrait;

class QueueLog extends AppBaseModel
{
	use ModelUpdateCountTrait;

    public $table = 'queue_log';

    protected $guarded = ['id'];

    protected $casts = [
        'start_time' => 'datetime',
		'finish_time' => 'datetime'
	];
}