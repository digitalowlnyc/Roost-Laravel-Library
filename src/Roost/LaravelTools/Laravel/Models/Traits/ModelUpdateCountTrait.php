<?php


namespace Roost\LaravelTools\Laravel\Models\Traits;

use Illuminate\Database\Eloquent\Model;

trait ModelUpdateCountTrait
{
	/**
	 * Laravel bootable trait
	 */
	static function bootModelUpdateCountTrait() {
		/** @var Model $static */
		$static = static::class;

		$static::creating(function ($model) {
			$model->db_update_count = 0;
		});

		$static::updating(function($model) {
			$model->db_update_count += 1;
		});
	}
}