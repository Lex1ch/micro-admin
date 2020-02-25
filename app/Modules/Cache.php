<?php

namespace App\Modules;

use Closure;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class Cache
{
    const PATH_CACHE = __DIR__ . '/../../storage/cache/app';

    /**
     * Комментаний: аналог remember в laravel
     *
     * @param string $key
     * @param int $time
     * @param Closure $callback
     * @return Closure
     */
    public static function remember(string $key, int $time, Closure $callback): Closure
    {
        $cache = new FilesystemAdapter(
            $namespace = '',
            $defaultLifetime = 0,
            $directory = self::PATH_CACHE
        );

        try {
            $value = $cache->get($key, function (ItemInterface $item) use ($callback, $time) {
                $item->expiresAfter($time);

                return $callback();
            });
        } catch (InvalidArgumentException $e) {
            return $callback();
        }

        return $value ?? $callback();
    }

    /**
     * Комментаний: удаляем кеш по ключу
     *
     * @param string $key
     * @return bool
     */
    public static function delete(string $key): bool
    {
        $cache = new FilesystemAdapter(
            $namespace = '',
            $defaultLifetime = 0,
            $directory = self::PATH_CACHE
        );

        try {
            return ($cache->delete($key));
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }
}