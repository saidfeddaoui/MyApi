<?php

date_default_timezone_set($_SERVER['APP_TIMEZONE']);

if (!function_exists('dd')) {
    function dd()
    {
        dump(...func_get_args());
        die;
    }
}
