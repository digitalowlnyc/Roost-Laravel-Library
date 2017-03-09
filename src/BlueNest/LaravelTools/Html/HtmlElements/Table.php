<?php
/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace BlueNest\LaravelTools\Html\HtmlElements;

class Table
{
    private $data = array();
    private $classes = array();
    private $iteratorIndex = 0;
    private $prettyHeaders = true;

    function append($tableRow) {
        $this->data[] = $tableRow;
    }

    function addClass($class) {
        $this->classes[] = $class;
    }

    function extractHeader() {
        if(count($this->data)) {
            $this->iterate();
            $firstRow = $this->next();
            return $firstRow;
        }
    }

    function getTableRow($idx) {
        return $this->data[$idx];
    }

    function iterate() {
        $this->iteratorIndex = 0;
    }

    function next() {
        return $this->getTableRow($this->iteratorIndex++);
    }

    function prettifyHeader($col) {
        return ucwords(str_replace('_', ' ', $col));
    }

    function toHtml() {
        $classAttribute = implode(' ', $this->classes);
        if(strlen($classAttribute)) {
            $classAttribute = 'class="' . $classAttribute . '"';
        }
        $html = '<table ' . $classAttribute . '>';

        $header = $this->extractHeader();

        $html .= '<tr>';
        foreach($header->getHeaders() as $cell) {
            if($cell instanceof Html) {
                $cell = $cell->toHtml();
            }
            if($this->prettyHeaders) {
                $cell = $this->prettifyHeader($cell);
            }
            $html .= '<th>' . $cell . '</th>';
        }
        $html .= '</tr>';

        foreach($this->data as $tableRow) {
            $html .= '<tr>';
            foreach($tableRow->getCells() as $cell) {
                if($cell instanceof HtmlElementInterface) {
                    $cell = $cell->toHtml();
                }
                $html .= '<td>' . $cell . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</table>';
        return $html;
    }
}

class TableRow
{
    private $values = array();
    private $headers = array();

    function append($cellValue, $cellHeader = null) {
        $this->values[] = $cellValue;
        if($cellHeader !== null) {
            $this->headers[] = $cellHeader;
        }
    }

    function appendAll($cellValueArray) {
        foreach($cellValueArray as $colName => $colValue) {
            $this->headers[] = $colName;
            $this->values[] = $colValue;
        }
    }

    function getCells() {
        return $this->values;
    }

    function getHeaders() {
        return $this->headers;
    }
}