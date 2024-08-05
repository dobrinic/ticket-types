<?php

namespace Core;

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];


$routes = require base_path('routes.php');

function routeToController($uri, $routes): void
{
    if (array_key_exists($uri, $routes)) {
        $controllerPath = $routes[$uri];
        [$controllerName, $methodName] = explode('@', $controllerPath);

        $controllerClass = 'Controllers\\' . $controllerName;

        $db = Database::get();
        $controller = new $controllerClass($db);

        $controller->$methodName();
    } else {
        abort();
    }
}

routeToController($uri, $routes);
