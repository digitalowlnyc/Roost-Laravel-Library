<?php
/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace BlueNest\LaravelTools\Helpers;

class ExceptionHelper
{
    static function getCallerBacktrace($file) {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5);
        $callerBacktraceEntry = null;

        foreach($backtrace as $backtraceEntry) {
            if(!in_array($backtraceEntry['file'], [__FILE__, $file])) {
                $callerBacktraceEntry = $backtraceEntry;
                break;
            }
        }

        return $callerBacktraceEntry;
    }

    static function getCallerBacktraceLogInfo($file) {
        $caller = self::getCallerBacktrace($file);
        if($caller !== null) {
            if(isset($caller['class']) && $caller['class'] != null) {
                $classPath = explode('\\', $caller['class']);
                $func = array_pop($classPath) . $caller['type'] . $caller['function'];
            } else {
                $func = $caller['function'];
            }
            $func = $func . '(...)';

            $fileLocation = basename($caller['file']) . '@' . $caller['line'];
            $logAppend = $fileLocation . '[' . $func . ']';
            return $logAppend;
        } else {
            return null;
        }
    }
}