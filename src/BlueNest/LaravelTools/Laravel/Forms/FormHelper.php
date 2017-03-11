<?php

/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace BlueNest\LaravelTools\Laravel\Forms;

use Illuminate\Http\Request;
use BlueNest\LaravelTools\Html\HtmlHelpers;

class FormHelper
{
    static function inputs(Request $request) {
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
}