<?php

require __DIR__ . '/../vendor/autoload.php';


function base_path($path = '/'): string
{
    return dirname(__DIR__) . DIRECTORY_SEPARATOR . $path;
}
