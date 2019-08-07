<?php

namespace Roost\LaravelTools\Laravel\Models;

use Illuminate\Database\Eloquent\Model;
use Roost\LaravelTools\Laravel\Models\Traits\AppBaseModelTrait;

class AppBaseModel extends Model {
	use AppBaseModelTrait;

	const CREATED_AT_COLUMN = "created_at";

	function __construct(array $attributes = []) {
		parent::__construct($attributes);
	}
}