<?php

 /**
     * [debug escreve em um arquivo log]
     * @param  [type] $var [variavel]
     * @return void
     */
    if (!function_exists('debug'))
    {
        function debug($var)
        {
            \Log::debug('<?:: START ::?>');
            if(is_string($var)) {
                \Log::debug($var);
            } else {
                \Log::debug(var_export($var, true));
            }
            \Log::debug('<?:: END ::?>');
        }
    }

    /**
     * [debugError escreve em um arquivo log]
     * @param  [exception] $e
     * @return void
     */
    if (!function_exists('debugError'))
    {
        function dbError($e)
        {
            debug(["error_message" => $e->getMessage(), "line" => $e->getLine(), "File" => $e->getFile()]);
        }
    }

    if (!function_exists('isValidDate'))
    {
        function isValidDate($date, $format)
        {
            $d = DateTime::createFromFormat($format, $date);
            return $d && $d->format($format) == $date;
        }
    }
