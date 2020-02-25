<?php

use UltraLite\Container\Container;
use App\Modules\Config;
use App\Providers\ServiceProviderInterface;
use App\Providers\AppServiceProvider;
use App\Providers\RouteProvider;

session_start([
    'cookie_lifetime' => 36000,
]);

/** Строим конфиг */
$config = new Config();

/** Создаем экземпляр контейнера */
$container = new Container([
    Config::class => function () use ($config) { return $config;},
]);

/** Определяем сервис-провайдеры */
$providers = [
    AppServiceProvider::class,
    RouteProvider::class,
];

/** Регистрируем сервисы */
foreach ($providers as $className) {
    if (!class_exists($className)) {
        /** @noinspection PhpUnhandledExceptionInspection */
        throw new Exception('Provider ' . $className . ' not found');
    }

    $provider = new $className;

    if (!$provider instanceof ServiceProviderInterface) {
        /** @noinspection PhpUnhandledExceptionInspection */
        throw new Exception($className . ' has not provider');
    }

    $provider->register($container);
}

/** Возвращаем контейнер */
return $container;