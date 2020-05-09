<?php

if (! function_exists('module')) {
    function module()
    {
        return app('module');
    }
}

if (! function_exists('role')) {
    function role()
    {
        return app('role');
    }
}
