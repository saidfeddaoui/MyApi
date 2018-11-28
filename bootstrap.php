<?php
//test git
date_default_timezone_set("Africa/Casablanca");

if (!function_exists('dd')) {
    function dd()
    {
        dump(...func_get_args());
        die;
    }
}
