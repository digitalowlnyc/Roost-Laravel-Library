<?php
/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace BlueNest\LaravelTools\Html\HtmlElements;

class Href implements HtmlElementInterface
{
    private $url = '';
    private $text = '';

    function __construct($url, $text = null) {
        $this->url = $url;
        if($text !== null) {
            $this->text = $text;
        } else {
            $this->text = $url;
        }
    }

    function toHtml()
    {
        $html = '<a href="' . $this->url . '">' . $this->text . '</a>';
        return $html;
    }
}