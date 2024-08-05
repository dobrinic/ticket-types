<?php

use JetBrains\PhpStorm\NoReturn;

function base_path($path = '/'): string
{
    return dirname(__DIR__) . DIRECTORY_SEPARATOR . $path;
}

#[NoReturn]
function dump($var): void
{
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
}

#[NoReturn]
function dd($var): void
{
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    die;
}

function env($key, $default = null)
{
    $value = getenv($key);
    if ($value === false) {
        return $default;
    }
    return $value;
}

#[NoReturn]
function abort($code = 404): void
{
    http_response_code($code);

    require base_path("views/{$code}.php");

    die();
}

function view($path, $attributes = []): void
{
    extract($attributes);

    require base_path('views/' . $path . '.php');
}

#[NoReturn]
function redirect($path): void
{
    header("location: {$path}");
    exit();
}

#[NoReturn]
function goBack(): void
{
    header("location: {$_SERVER['HTTP_REFERER']}");
    exit();
}
