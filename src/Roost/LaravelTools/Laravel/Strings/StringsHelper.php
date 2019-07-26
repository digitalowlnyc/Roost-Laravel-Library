<?php
/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace Roost\LaravelTools\Laravel\Strings;

class StringsHelper {
    public static function quantityText($singularNoun, $count) {
        return $count . ' ' . str_plural($singularNoun, $count);
    }
}
