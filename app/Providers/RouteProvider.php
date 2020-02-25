<?php

namespace App\Providers;

use UltraLite\Container\Container;
use Psr\Container\ContainerInterface;
use App\Modules\Route;
use App\Modules\Config;

class RouteProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container->set(Route::class, function (ContainerInterface $container) {
            return new Route($container->get(Config::class)->get('route.cache'));
        });
    }
}