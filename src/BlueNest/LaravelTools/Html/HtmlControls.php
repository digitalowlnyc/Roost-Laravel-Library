<?php
/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace BlueNest\LaravelTools\Html;

class HtmlControls
{
    public static function attributesFromArray($attributeMap) {
        $attributes = '';
        foreach($attributeMap as $k=>$v) {
            $attributes .= ' ';
            if(is_bool($v)) {
                if($v) {
                    $attributes .= $k;
                }
            } else {
                $attributes .= $k . "=" . surround($v);
            }
        }
        return $attributes;
    }

    private static function getValue($type, $inputs) {
        if(isset($inputs['value'])) {
            $value = $inputs['value'];
        } else {
            if($type === 'slider') {
                $value = '-';
            } else {
                $value = '';
            }
        }
        return $value;
    }

    private static function extractRange($rangeDefinition) {
        if(count($rangeDefinition) !== 2) {
            throw new \Exception('Expecting range to be two elements');
        }

        $range = [];
        $range['begin'] = $rangeDefinition[0];
        $range['end'] = $rangeDefinition[1];

        if(!is_int($range['begin']) || !is_int($range['end'])) {
            throw new \Exception('Expecting range values to be integers');
        }

        return $range;
    }

    public static function createControl($field, $inputs, $extraCssClasses = array()) {
        $type = $inputs['type'];
        $value = self::getValue($type, $inputs);

        if(gettype($value) === 'array') {
            $controls = [];
            foreach($value as $val) {
                $inputs['value'] = $val[$inputs['key']];
                $controls[] = HtmlControls::createControl($field, $inputs, ['collection', 'inline']);
            }
            return implode('', $controls);
        }

        $cssClasses = array('dynamic-input');
        $cssClasses = array_merge($cssClasses, $extraCssClasses);

        if($value === null) {
            $cssClasses[] = 'hidden';
        }

        if(!empty($inputs['attrs']['required'])) {
            $cssClasses[] = 'required';
        }

        $fieldName = 'field_' . $field;
        $common = "id='$fieldName' name= '$fieldName' class='" . implode(" ", $cssClasses) . "'";

        if(isset($inputs['attrs'])) {
            $common .= self::attributesFromArray($inputs['attrs']);
        }

        if($type === 'plain-text') {
            $html = "<div $common>{$value}</div>";
        } else if(in_array($type, ['text', 'button'])) {
            $html = "<input $common type='$type' value='{$value}'>";
        } else if($type ==='textarea') {
            $html = "<textarea $common>{$value}</textarea>";
        } elseif($type == 'slider') {
            $outputControlId = $field . '_output';
            $html = "<input $common type='range' min='{$inputs['range'][0]}' max='{$inputs['range'][1]}' oninput='{$outputControlId}.value = this.value'>";
            $html .= "<output name='{$outputControlId}' id='$outputControlId'>{$value}</output>";
        } elseif($type == 'select' || $type == 'list-select') {
            if(isset($inputs['range'])) {
                $options = [];
                $range = self::extractRange($inputs['range']);
                for($x = $range['begin']; $x <= $range['end']; $x++) {
                    $options[] = $x;
                }
            } else if(isset($inputs['values'])) {
                $options = $inputs['values'];
            } else {
                throw new \Exception('Select control must specify either values or range of values');
            }

            if(($type === 'list-select')) {
                $common .= " size='" . count($options) . "'";
            }

            $html = "<select $common value='{$value}'>";

            foreach($options as $opt) {
                $selected = $value == $opt ? ' selected' : '';
                $html .= "<option{$selected} value='$opt'>$opt</option>";
            }
            $html .= "</select>";
        } elseif($type == 'bool') {
            $html = "<select $common>";
            $trueDisplayValue = $inputs['values'][0];
            $falseDisplayValue = $inputs['values'][1];
            $html .= "<option value='1'>$trueDisplayValue</option>";
            $html .= "<option value='0'>$falseDisplayValue</option>";
            $html .= "</select>";
        } else {
            throw new \Exception('Unhandled control type: ' . json_encode($type));
        }

        return $html;
    }

    /**
     * Guesses a display version of the field name based on the column
     * name in the database
     *
     * @param $fields
     * @return mixed
     */
    public static function addDisplayNames($fields) {
        foreach($fields as $field=>&$inputs) {
            if(!isset($inputs['display-name'])) {
                $inputs['display-name'] = ucwords(str_replace('_', ' ', $field));
            }
        }
        return $fields;
    }
}