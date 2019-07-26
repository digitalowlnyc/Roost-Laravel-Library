<?php

namespace Roost\LaravelTools\Helpers;


class FormHelper
{
	public static function combineArrayInputs($inputData, $resultKey = "combined") {
		$arrayData = [];
		foreach($inputData as $inputField => $inputFieldValue) {
			if(is_array($inputFieldValue)) {
				$arrayData[$inputField] = $inputFieldValue;
				unset($inputData[$inputField]);
			}
		}

		$combined = [];
		foreach($arrayData as $field => $values) {
			foreach($values as $x => $value) {
				if(!isset($combined[$x])) {
					$combined[$x] = [];
				}
				$combined[$x][$field] = $value;
			}
		}

		$inputData[$resultKey] = $combined;

		return $inputData;
	}
}