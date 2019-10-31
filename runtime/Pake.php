<?php

if (!function_exists('desc')) {
    function desc(string $description)
    {
        Pake\Pake::desc($description);
    }
}

if (!function_exists('task')) {
    function task(string $name, callable $callable)
    {
        Pake\Pake::task($name, $callable);
    }
}