<?php

/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace BlueNest\LaravelTools\Laravel\Exceptions;

class JsonEncodingException extends \Exception
{
    function __construct($dataEncoded) {
        $message = 'Could not encoded the following as JSON: ' . print_r($dataEncoded, true);
        parent::__construct($message, 0, null);
    }
}