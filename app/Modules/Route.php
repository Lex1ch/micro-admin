<?php

namespace App\Modules;

use FastRoute;

class Route
{
    protected $router;

    protected $cache;

    /** @noinspection PhpUnusedParameterInspection */
    public function __construct(string $cache = __DIR__ . '/../../storage/cache/routes/route.php')
    {
        $this->cache = $cache;
        $this->router = FastRoute\cachedDispatcher(function(FastRoute\RouteCollector $route) {
            require_once __DIR__ . '/../route.php';
        }, [
            'cacheFile' => $cache,
        ]);
    }

    public function run(): void
    {
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }

        $uri = rawurldecode($uri);
        $routeInfo = $this->router->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
                die("404");
                break;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                //$allowedMethods = $routeInfo[1];
                die("Method not allowed");
                break;
            case FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                $vars[] = Http::request();
                [$class, $method] = explode("@", $handler, 2);
                $class = 'App\\Controllers\\' . $class;
                call_user_func_array(array(new $class, $method), $vars);
                break;
        }
    }
}