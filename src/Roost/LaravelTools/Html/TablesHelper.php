<?php
/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace Roost\LaravelTools\Html;

class Tables {
    /**
     * Prints a table (array of maps)
     * @param $table
     */
    static function printTable($table, $options = ["pad-row-length" => false, "cell-callback" => null], $returnTheHtml = false) {
        $output = "";
        $output .= "<table border='1'>";
        $rowCount = 0;

        $shouldPadRowLength = isset($options["pad-row-length"]) ? $options["pad-row-length"] : false;
        if($shouldPadRowLength) {
            $maxRowLength = self::maxRowLength($table);
        }

        $cellCallback = isset($options["cell-callback"]) ? $options["cell-callback"] : null;

        $firstRow = array_shift($table);
        array_unshift($table, $firstRow, $firstRow);

        foreach($table as $row) {
            $rowCount++;

            $tag = ($rowCount === 1) ? "th" : "td";

            if($shouldPadRowLength && count($row) < $maxRowLength) {
                $row = array_pad($row, $maxRowLength, "");
            }

            $output .= "<tr>";
            foreach($row as $colName =>$colValue) {
                $val = $rowCount === 1 ? $colName : $colValue;

                if($cellCallback !== null && is_callable($cellCallback)) {
                    $val = $cellCallback($val);
                }

                $output .= "<$tag>$val</$tag>";
            }
            $output .= "</tr>";
        }

        $output .= "</table>";
        if($returnTheHtml) {
            return $output;
        } else {
            echo $output;
        }

    }

    static function getSizeTableNestedRow($row) {
        if(gettype($row) === "array") {
            return count($row);
        } else {
            return count(array_keys($row));
        }
    }

    static function maxRowLength($table) {
        $max = 0;
        array_walk($table, function($nestedRow) use (&$max) {
            $max = max($max, self::getSizeTableNestedRow($nestedRow));
        });
        return $max;
    }
}