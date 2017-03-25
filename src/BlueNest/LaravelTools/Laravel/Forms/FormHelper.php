<?php

/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace BlueNest\LaravelTools\Laravel\Forms;

use BlueNest\LaravelTools\Laravel\Logging\UserLog;
use Illuminate\Http\Request;
use BlueNest\LaravelTools\Html\HtmlHelpers;

class FormHelper
{
    private static $loggingOn = false;

    static function inputsForLogging(Request $request, $asJson = true) {
        $inputsArray = $request->all();

        $filteredKeys = ['password', 'password_confirmation'];

        array_walk($inputsArray, function(&$item, $key) use($filteredKeys) {
            if(in_array(strtolower($key), $filteredKeys)) {
                $item = '[hidden]';
            }
        });

        if($asJson) {
            return json_encode($inputsArray);
        } else {
            return $inputsArray;
        }
    }

    static function inputs(Request $request) {
        if(self::$loggingOn) {
            UserLog::info('Form inputs are: ' . self::inputsForLogging($request));
        }
        $values = $request->all();
        $values = array_map(function($item) {
            if(strtolower($item) === 'true') {
                return true;
            }
            if(strtolower($item) === 'false') {
                return false;
            }
            return $item;
        }, $values);
        return $values;
    }

    static function parseActionSingleInput($actionLabel, $requestParams) {
        $actionsMap = self::parseActionInput($requestParams);
        if(!isset($actionsMap[$actionLabel]) || empty($actionsMap[$actionLabel])) {
            return false;
        }
        $actions = $actionsMap[$actionLabel];
        reset($actions);
        $first_key = key($actions);
        return [
            'id' => $first_key,
            'value' => $actions[$first_key]
        ];
    }

    static function parseActionInput($requestParams) {
        $re = '/do-action-input-([a-zA-Z_]+)-item-([A-Za-z0-9]+)/';
        $actionsMap = [];
        foreach($requestParams as $postFieldName => $postFieldValue) {
            if(preg_match($re, $postFieldName, $matches) === 1) {
                $actionLabel = $matches[1];
                $itemId = $matches[2];
                if(!isset($actionsMap[$actionLabel])) {
                    $actionsMap[$actionLabel] = [];
                }
                $actionsMap[$actionLabel][$itemId] = boolval($postFieldValue);
            }
        }
        return $actionsMap;
    }

    static function createActionInput($actionLabel, $itemId = null, $inputValue = false) {
        $inputType = HtmlHelpers::hiddenInput();
        $inputIdAndName = 'do-action-input-' . $actionLabel . '-item-' . $itemId;

        $inputValue = boolval($inputValue);

        $html = "<input type='{$inputType}' name='{$inputIdAndName}' id='{$inputIdAndName}' value='{$inputValue}'>";
        return $html;
    }

    static function setLogging($val) {
        self::$loggingOn = $val;
    }
}