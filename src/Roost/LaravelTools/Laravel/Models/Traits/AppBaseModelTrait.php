<?php

namespace Roost\LaravelTools\Laravel\Models\Traits;

trait AppBaseModelTrait {
	function __construct(array $attributes = []) {
		parent::__construct($attributes);
		$this->appendColumns([
			"created_at_formatted"
		]);
	}

	public static function last($number = null) {
		$shouldReturnACollection = ($number !== null);

		$number = $number ?? 1;

		$createdAtColumn = static::CREATED_AT_COLUMN;

		$query = self::limit($number)->orderBy($createdAtColumn, "desc");
		if(!$shouldReturnACollection) {
			return $query->first();
		} else {
			return $query->get();
		}
	}

	public static function first($number = null) {
		$shouldReturnACollection = ($number !== null);

		$number = $number ?? 1;

		$createdAtColumn = static::CREATED_AT_COLUMN;

		$query = self::limit($number)->orderBy($createdAtColumn, "desc");
		if(!$shouldReturnACollection) {
			return $query->first();
		} else {
			return $query->get();
		}
	}

	public static function ids() {
		return self::select("id")->orderBy(static::CREATED_AT_COLUMN)->get()->pluck("id");
	}

	public static function atIndex($index) {
		$allIds = static::ids();

		$lastIndex = count($allIds) - 1;

		if($index < 0 || $index > $lastIndex) {
			throw new \RuntimeException("Invalid index, valid range: 0 to " . $lastIndex);
		}

		$id = $allIds[$index];

		return self::find($id);	}

	public static function atLastIndex($indexFromEnd) {
		$allIds = static::ids();

		$lastIndex = count($allIds) - 1;

		$index = $lastIndex - $indexFromEnd;

		if($index < 0 || $index > $lastIndex) {
			throw new \RuntimeException("Invalid index, valid range: 0 to " . $lastIndex);
		}

		$id = $allIds[$index];

		return self::find($id);
	}

	public function getCreatedAtFormattedAttribute() {
		if($this->created_at !== null) {
			return $this->created_at->format("m/j/Y H:i:s");
		} else {
			return "null";
		}
	}

	public function appendColumns($appendArray) {
		foreach($appendArray as $append) {
			$this->appends[] = $append;
		}
	}

	public static function applyAndSave(callable $callable) {
		$all = static::all();

		foreach($all as $model) {
			$callable($model);
			$model->save();
		}
	}
}