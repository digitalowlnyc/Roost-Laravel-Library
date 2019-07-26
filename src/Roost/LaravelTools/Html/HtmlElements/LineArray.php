<?php
/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace Roost\LaravelTools\Html\HtmlElements;

class LineArray implements HtmlElementInterface
{
    private $data = null;
    private $lineBreak = '<br>';

    function __construct($dataArray) {
        $this->data = $dataArray;
    }

    function toHtml()
    {
        return implode($this->lineBreak, $this->data);
    }
}